<?php

namespace Pearls\Modules\City\Http\Requests;

use Pearls\Base\Http\Requests\BaseRequest;
use Pearls\Modules\City\Models\City;

class CityRequest extends BaseRequest
{
    public function authorize()
    {
        
        $this->setModel(City::class);

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
                ]
            );
        }

        return $rules;
    }
}