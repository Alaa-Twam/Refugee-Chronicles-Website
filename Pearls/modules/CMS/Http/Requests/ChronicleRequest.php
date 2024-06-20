<?php

namespace Pearls\Modules\CMS\Http\Requests;

use Pearls\Base\Http\Requests\BaseRequest;
use Pearls\Modules\CMS\Models\Chronicle;

class ChronicleRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Chronicle::class);

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
                    'title' => 'required|max:255',
                    'city_id' => 'required',
                    'youtube_link' => 'required',
                    'description' => 'required',
                    'status' => 'required',
                ]
            );
        }

        return $rules;
    }

    public function messages() {
        return [
            'city_id.required' => 'Please select a city by clicking inside the map.',
        ];
    }
}
