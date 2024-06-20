<?php

namespace Pearls\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Model for the current request.
     * @var
     */
    protected $model;
    protected $ability = null;
    protected $policySecondArgument;

    protected function setModel($class, $routeName = '')
    {
        if (empty($routeName)) {
            $routeName = strtolower(class_basename($class));
        }

        $model = $this->route($routeName);

        $model = $model ?? $class;

        $this->model = $model;
    }

    /**
     * Check if the user can continue in the request or not.
     * @param $action
     * @return bool
     */
    protected function can($action)
    {
        $policySecondArgument = $this->policySecondArgument ?? null;

        if (!empty($policySecondArgument)) {
            return user()->can($action, [$this->model, $policySecondArgument]);
        } else {
            return user()->can($action, $this->model);
        }
    }

    protected function isAuthorized()
    {
        if (isSuperUser()) {
//            return true;
        }

        if (!empty($this->ability)) {
            return $this->can($this->ability);
        }

        if ($this->isCreate() || $this->isStore() || $this->isUpdateOrCreate()) {
            // Determine if the user is authorized to create an item,
            return $this->can('create');
        }

        if ($this->isEdit() || $this->isUpdate() || $this->isUpdateOrCreate()) {
            // Determine if the user is authorized to update an item,
            return $this->can('update');
        }

        if ($this->isDelete()) {
            // Determine if the user is authorized to delete an item,
            return $this->can('destroy');
        }

        // Determine if the user is authorized to view this model,
        return $this->can('view');
    }

    /**
     * Check the process is create.
     *
     * @return bool
     **/
    protected function isCreate()
    {
        if ($this->is('*/create*')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is store.
     *
     * @return bool
     **/
    protected function isStore()
    {
        if ($this->isMethod('POST') && !$this->isUpdateOrCreate()) {
            return true;
        }
        return false;
    }

    protected function isUpdateOrCreate()
    {
        if ($this->isMethod('POST') && $this->is('*update-create')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is edit.
     *
     * @return bool
     **/
    protected function isEdit()
    {
        if ($this->is('*/edit')) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is update.
     *
     * @return bool
     **/
    protected function isUpdate()
    {
        if ($this->isMethod('PUT') ||
            $this->isMethod('PATCH')
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check the process is verify.
     *
     * @return bool
     **/
    protected function isDelete()
    {
        if ($this->isMethod('DELETE')) {
            return true;
        }
        return false;
    }

    public function rules()
    {
        $rules = [];

        if ($this->isUpdate() || $this->isStore()) {
            if (method_exists($this->model, 'customFieldSettings')) {

                $model = $this->model;

                if (is_string($model)) {
                    $model = new $this->model;
                }

                $customFields = $model->customFieldSettings();

                foreach ($customFields as $field) {
                    $validation_rules = $field->validation_rules;

                    if ($field->required && !str_contains($validation_rules, 'required')) {
                        $validation_rules = 'required|' . $validation_rules;
                    }

                    if (!empty($validation_rules)) {
                        $rules[$field->name] = $validation_rules;
                    }
                }
            }
        }

        return $rules;
    }
}
