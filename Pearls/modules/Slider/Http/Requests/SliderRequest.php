<?php

namespace Pearls\Modules\Slider\Http\Requests;

use Pearls\Base\Http\Requests\BaseRequest;
use Pearls\Modules\Slider\Models\Slider;

class SliderRequest extends BaseRequest
{
    public function authorize()
    {
        
        $this->setModel(Slider::class);

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

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:255',
                'caption' => 'max:512',
                'status' => 'required',
                'image' => 'required|file|mimes:jpg,jpeg,png,gif'
                ]
            );
        }

        if ($this->isUpdate()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:255',
                'caption' => 'max:512',
                'status' => 'required',
                'image' => 'file|mimes:jpg,jpeg,png,gif'
                ]
            );
        }

        return $rules;
    }
}