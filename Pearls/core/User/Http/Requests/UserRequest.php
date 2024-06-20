<?php

namespace Pearls\User\Http\Requests;

use Pearls\Base\Http\Requests\BaseRequest;
use Pearls\User\Models\User;

class UserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('user');

        if ($this->isEdit() && !is_null($this->model)) {
            if (!isSuperUser() && isSuperUser($this->model) && $this->model->id != user()->id) {
                return false;
            }
        }

        $this->model = is_null($this->model) ? User::class : $this->model;

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(User::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                    'roles' => 'required',
                    'status' => 'required',
                    'first_name' => 'required|max:30',
                    'last_name' => 'required|max:30',
                ]
            );
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'username' => 'required|string|max:30|min:5|unique:users',
                    'email' => 'required|email|max:255|unique:users,email',
                    'password' => 'required|confirmed|max:255|min:6'
                ]
            );
        }

        if ($this->isUpdate()) {
            $user = $this->route('user');

            $rules = array_merge($rules, [
                    'email' => 'required|email|max:255|unique:users,email,' . $user->id,
//                    'username' => 'required|string|max:30|min:5|unique:users,username,' . $user->id,
                    'password' => 'nullable|confirmed|max:255|min:6'
                ]
            );
        }

        return $rules;
    }
}
