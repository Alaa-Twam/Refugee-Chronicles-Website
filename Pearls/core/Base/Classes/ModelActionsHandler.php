<?php

namespace Pearls\Base\Classes;

class ModelActionsHandler
{
    public function eval_array($array, $object = null)
    {
        return array_map(function ($ele) use ($object) {
            return eval($ele);
        }, $array);
    }

    public function isActionVisible($action, $model)
    {
        if (empty($action['policies']) && empty($action['permissions'])) {
            return true;
        }

        $isVisible = false;

        if (!user()) {
            return $isVisible;
        }

        if (!empty($action['policies'])) {
            foreach ($action['policies'] as $policy) {
                $policyModel = $model;

                if (\Arr::has($action, 'policies_args')
                    && (is_null($action['policies_args']) || !empty($action['policies_args']))
                ) {
                    $policyArguments = $action['policies_args'];
                } elseif (\Arr::has($action, 'policies_args_relation')) {
                    $policyArguments = $model->{$action['policies_args_relation']};
                } else {
                    $policyArguments = $model;
                }

                if (!empty($action['policies_model'])) {
                    $policyModel = $action['policies_model'];
                }

                if ($policyModel != $policyArguments) {
                    $policyModel = is_object($policyModel) ? get_class($policyModel) : $policyModel;

                    if (user()->can($policy, [$policyModel, $policyArguments])) {
                        $isVisible = true;
                        break;
                    }
                } else {
                    if (user()->can($policy, $policyModel)) {
                        $isVisible = true;
                        break;
                    }
                }
            }
        }

        if (!empty($action['permissions'])) {
            foreach ($action['permissions'] as $permission) {
                if (user()->hasPermissionTo($permission)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }

    public function patternSolver($array, $model)
    {
        $keys = array_keys($array);

        $patternKeys = preg_grep('/^.*_pattern/', $keys);

        if (!empty($patternKeys)) {
            foreach ($patternKeys as $key) {
                $array[$key];
                $pattern = $array[$key]['pattern'];
                $replace = $array[$key]['replace'];
                $replaceResult = \Str::replaceArray('[arg]', $this->eval_array($replace, $model), $pattern);
                $resultKey = str_replace('_pattern', '', $key);
                $array[$resultKey] = $replaceResult;
                unset($array[$key]);
            }
        }

        return $array;
    }

    public function solveActionPatterns($action, $model)
    {
        $action = $this->patternSolver($action, $model);

        //for now only one level supported
        foreach ($action as $key => $value) {
            if (is_array($value) && !\Str::is('*_pattern', $key)) {
                $action[$key] = $this->patternSolver($value, $model);
            }
        }

        return $action;
    }

    /**
     * @param $actions
     * @param null $view
     * @return string
     * @throws \Throwable
     */
    public function renderActions($actions, $view = null)
    {
        $actionsView = 'admin.components.actions_buttons';

        if (!is_null($view) && view()->exists($view)) {
            $actionsView = $view;
        }

        return view($actionsView)->with(compact('actions'))->render();
    }
}
