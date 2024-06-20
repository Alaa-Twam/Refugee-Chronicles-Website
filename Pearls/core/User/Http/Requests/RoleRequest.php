<?php

namespace Pearls\User\Http\Requests;

use Pearls\Base\Http\Requests\BaseRequest;
use Pearls\User\Models\Role;

class RoleRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('role');

        $this->model = is_null($this->model) ? Role::class : $this->model;

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:255',
                    'type' => 'required',
                ]
            );
        }

        return $rules;
    }
}
