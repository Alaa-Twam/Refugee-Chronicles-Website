<?php

namespace Pearls\Base\Classes;

use Collective\Html\FormFacade as Form;
use Collective\Html\HtmlFacade as Html;
use Illuminate\Support\HtmlString;

class PearlsForm
{
    const CONTROLS_CLASS = 'form-control form-white ';
    const INPUT_GROUP_CLASS = 'input-group ';
    const INPUT_GROUP_ADDON_CLASS = 'input-group-addon ';
    const ERROR_SPAN_CLASS = 'help-block ';
    const HELP_TEXT_CLASS = 'help-text text-muted ';
    const REQUIRED_FIELD_CLASS = 'required-field ';
    const FORM_GROUP_CLASS = 'form-group ';
    const FORM_GROUP_ERROR_CLASS = 'has-error has-feedback ';
    const FILE_CLASS = 'btn btn-info btn-file ';
    const SPACER = '&nbsp;&nbsp;';

    protected $skipValueTypes = ['file', 'password'];
    protected $isCheckboxRadio = ['checkbox', 'radio'];
    protected $selectTypes = ['boolean', 'select', 'select2'];

    public function __construct()
    {
    }

    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    public function label($key, $label, $attributes = [])
    {
        if (empty($label)) {
            return '';
        }
        return Form::label($key, trans($label), $attributes);
    }

    public function helpText($text)
    {
        return '<small class="' . self::HELP_TEXT_CLASS . '">' . $text . '</small>';
    }

    public function inputAddon($addon)
    {
        if (empty($addon)) {
            return '';
        } else {
            return '<span class="' . self::INPUT_GROUP_ADDON_CLASS . '">' . $addon . '</span>';
        }
    }

    public function errorMessage($key)
    {
        $error = '';

        $errors = view()->shared('errors');

        if (!is_null($errors) && count($errors) > 0 && $errors->has($key)) {
            $error = '<span class="' . self::ERROR_SPAN_CLASS . '">' . $errors->first($key) . '</span>';
        }

        return $error;
    }

    public function formGroup($content, $required = false, $error = null, $class = '')
    {
        if (empty($class)) {
            $class = self::FORM_GROUP_CLASS;
        }
        if ($required) {
            $class .= self::REQUIRED_FIELD_CLASS;
        }

        if (!empty($error)) {
            $class .= self::FORM_GROUP_ERROR_CLASS;
            $content = $content . $error;
        }

        return '<div class="' . $class . '">' . $content . '</div>';
    }

    public function input($key, $label = '', $required = false, $value = null, $attributes = [], $type)
    {
        $attributes['class'] = self::CONTROLS_CLASS . \Arr::get($attributes, 'class', '');

        $attributes['placeholder'] = trans(\Arr::get($attributes, 'placeholder', $label ?? ''));

        $attributes['id'] = \Arr::get($attributes, 'id', $key);

        $attributes = $this->setDataAttribute($attributes);

        $help_text = \Arr::pull($attributes, 'help_text', '');

        if (!empty($help_text)) {
            $help_text = $this->helpText($help_text);
        }
        $left_addon = \Arr::pull($attributes, 'left_addon', '');
        $right_addon = \Arr::pull($attributes, 'right_addon', '');

        if (!empty($left_addon) || !empty($right_addon)) {
            $left_addon = $this->inputAddon($left_addon);
            $right_addon = $this->inputAddon($right_addon);
        }

        //remove empty empty attributes
        $attributes = array_filter($attributes, function ($value) {
            return $value !== '';
        });

        // in case selectTypes, radio, checkboxes
        $options = \Arr::pull($attributes, 'options', []);

        if (in_array($type, $this->selectTypes)) {
            $input = Form::select($key, $options, $value, array_merge([], $attributes));
        } elseif (in_array($type, $this->skipValueTypes)) {
            $input = Form::{$type}($key, array_merge([], $attributes));
            if ($type == 'file') {
                $input = '<div class="upload-file-area" data-input="' . $attributes['id'] . '"><span class="' . self::FILE_CLASS . '"><i class="fa fa-folder-open-o"></i> Browse' . $input . '</span>' . self::SPACER . '<span class="file-name"></span><img  src="#" alt="" class="preview hidden" width="100"/></div>';
            }
        } elseif ($type == 'checkbox') {
            $checked = \Arr::pull($attributes, 'checked', false);

            $wrapper_tag = \Arr::pull($attributes, 'wrapper_tag', 'label');

            $input = "<$wrapper_tag>" . Form::{$type}($key, $value, $checked, array_merge([], $attributes)) . self::SPACER . trans($label) . "</$wrapper_tag>";
            $label = '';
        } elseif ($type == 'checkboxes') {
            $selected = $value;
            $input = '<div>';
            $wrapper_tag = \Arr::pull($attributes, 'wrapper_tag', 'label');
            foreach ($options as $checkbox_value => $checkbox_label) {
                $attributes['id'] = $checkbox_value . '_' . \Str::random(6);
                $input .= "<$wrapper_tag>" . Form::checkbox($key, $checkbox_value, in_array($checkbox_value, $selected), array_merge([], $attributes)) . self::SPACER . $checkbox_label . "</$wrapper_tag>" . self::SPACER;
            }
            $input = $input . '</div>';
        } elseif ($type == 'radio') {
            $selected = $value;
            $input = '<div>';
            $wrapper_tag = \Arr::pull($attributes, 'wrapper_tag', 'label');
            foreach ($options as $radio_value => $radio_label) {
                $attributes['id'] = $radio_value . '_' . \Str::random(6);
                $input .= "<$wrapper_tag>" . Form::radio($key, $radio_value, $radio_value == $selected, array_merge([], $attributes)) . self::SPACER . $radio_label . "</$wrapper_tag>" . self::SPACER;
            }
            $input = $input . '</div>';
        } elseif ($type == 'date_range') {
            $input = '<div class="input-group input-daterange" data-autoclose="true" data-date-format="yyyy-mm-dd">';
            $input .= $this->date($key . "['from']", '', $required, $value, $attributes);
            $input .= '<div class="input-group-addon">to</div>';
            $input .= $this->date($key . "['to']", '', $required, $value, $attributes);
            $input .= '</div>';

        } else {
            $input = Form::{$type}($key, $value, array_merge([], $attributes));
        }

        $label = $this->label($key, $label);

        if (!empty($left_addon) || !empty($right_addon)) {
            $input = '<div class="' . self::INPUT_GROUP_CLASS . '">' . $left_addon . $input . $right_addon . '</div>';
        }

        $response = $label . $input . $help_text;

        return $this->toHtmlString($this->formGroup($response, $required, $this->errorMessage($key)));
    }

    public function checkbox($key, $label = '', $checked = false, $value = 1, $attributes = [])
    {
        $attributes['value'] = $value;
        $attributes['checked'] = $checked;

        return $this->input($key, $label, false, $value, $attributes, 'checkbox');
    }

    public function checkboxes($key, $label = '', $required = false, $options, $selected, $attributes = [])
    {
        $options = $this->getArrayOf($options);
        $attributes['options'] = $options;
        return $this->input($key, $label, $required, $selected, $attributes, 'checkboxes');
    }

    public function radio($key, $options, $label = '', $required = false, $selected = null, $attributes = [])
    {
        $options = $this->getArrayOf($options);
        $attributes['options'] = $options;
        return $this->input($key, $label, $required, $selected, $attributes, 'radio');
    }

    public function text($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'text');
    }


    public function date($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        $attributes['class'] = \Arr::get($attributes, 'class', 'b-datepicker inputmask ');

        if (!str_contains($attributes['class'], 'date')) {
            $attributes['class'] .= ' b-datepicker inputmask ';
        }
        $attributes = array_merge(['data-date-format' => 'yyyy-mm-dd', 'data-inputmask-alias' => "datetime", "data-inputmask-inputformat" => "yyyy-mm-dd"], $attributes);

        return $this->input($key, $label, $required, $value, $attributes, 'text');
    }

    public function dateRange($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'date_range');
    }

    public function textarea($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'textarea');
    }

    public function number($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'number');
    }

    public function email($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        return $this->input($key, $label, $required, $value, $attributes, 'email');
    }

    public function password($key, $label = '', $required = false, $attributes = [])
    {
        return $this->input($key, $label, $required, null, $attributes, 'password');
    }

    public function boolean($key, $label = '', $required = false, $value = null, $attributes = [])
    {
        $options = \Arr::pull($attributes, 'options', ['true' => 'True', 'false' => 'False']);

        return $this->select($key, $label, $options, $required, $value, $attributes, 'boolean');
    }

    public function select($key, $label = '', $options = [], $required = false, $value = null, $attributes = [], $type = 'select')
    {

        if (empty($label)) {
            $label = '';
        }

        $label = trans($label);

        $attributes['placeholder'] = \Arr::get($attributes, 'placeholder', ("Select $label..."));

        if ($type != 'select2') {
            $attributes['data-placeholder'] = \Arr::get($attributes, 'data-placeholder', ("Select $label..."));
        } else {
            $attributes['data-placeholder'] = \Arr::get($attributes, 'data-placeholder', $attributes['placeholder']);
            $attributes['placeholder'] = ''; // since placeholder not working for select2

            $options = $this->getArrayOf($options);

            if (!\Arr::get($attributes, 'multiple', false)) {
                //add empty option to enable select2 placeholder
                $options = ['' => ''] + $options;
            }
        }

        $attributes['options'] = $options;

        return $this->input($key, $label, $required, $value, $attributes, $type);
    }

    public function select2($key, $label = '', $options = [], $required = false, $value = null, $attributes = [])
    {
        if (\Arr::get($attributes, 'class', false)) {
            $attributes['class'] = $attributes['class'] . ' select2-normal';
        } else {
            $attributes['class'] = 'select2-normal';
        }
        return $this->select($key, $label, $options, $required, $value, $attributes, 'select2');
    }

    public function file($key, $label = '', $required = false, $value = null, $attributes = [])
    {
//        $attributes['class'] = \Arr::get($attributes, 'class', 'upload-file-preview ');
        return $this->input($key, $label, $required, $value, $attributes, 'file');
    }

    /**
     * get array from collection object if options passed as object
     * @param $options
     * @return array
     */
    protected function getArrayOf($options)
    {
        if (gettype($options) == 'object') {
            try {
                $options = $options->toArray();
            } catch (\Exception $exception) {
                $options = [];
            }
        } elseif (!is_array($options)) {
            $options = [];
        }

        return $options;
    }

    /**
     * @param $href
     * @param $label
     * @param array $attributes
     * @return HtmlString
     */
    public function link($href, $label, $attributes = [])
    {
        $html_attributes_array = ['href' => $href];

        $data = \Arr::pull($attributes, 'data', []);

        foreach ($data as $key => $value) {
            $html_attributes_array['data-' . $key] = $value;
        }

        $html_attributes = Html::attributes(array_merge($html_attributes_array, $attributes));

        return $this->toHtmlString('<a' . $html_attributes . '>' . $label . '</a>');
    }

    /**
     * @param $attributes
     * @return mixed
     */
    protected function setDataAttribute($attributes)
    {
        $data = \Arr::pull($attributes, 'data', []);

        foreach ($data as $key => $value) {
            $attributes['data-' . $key] = $value;
        }

        return $attributes;
    }

    /**
     * @param $label
     * @param array $attributes
     * @param string $type
     * @return HtmlString
     */
    public function button($label, $attributes = [], $type = 'button')
    {
        $html_attributes_array = ['type' => $type];

        $data = \Arr::pull($attributes, 'data', []);

        foreach ($data as $key => $value) {
            $html_attributes_array['data-' . $key] = $value;
        }

        $html_attributes = Html::attributes(array_merge($html_attributes_array, $attributes));

        return $this->toHtmlString('<button' . $html_attributes . '>' . $label . '</button>');
    }

    public function formButtons($label = '', $attributes = [], $cancelAttributes = [], $extraButtons = [])
    {
        $wrapper_class = \Arr::pull($attributes, 'wrapper_class', self::FORM_GROUP_CLASS . ' text-right');

        if (empty($label)) {
            $label = '<i class="fa fa-save"></i> ' . (view()->shared('title_singular') ?: '');
        }

        $buttons = '';

        if (!empty($extraButtons)) {
            foreach ($extraButtons as $extraButton) {
                $buttons .= $this->button($extraButton['label'], $extraButton['attributes'] ?? [], $extraButton['type'] ?? 'button');
            }
        }

        $buttons .= $this->button($label, array_merge(['class' => 'btn btn-success ajax-submit'], $attributes), 'submit');

        if (\Arr::get($cancelAttributes, 'show_cancel', true)) {
            $cancelLabel = \Arr::get($cancelAttributes, 'label', '<i class="fa fa-times"></i> Cancel');

            $cancelHrefDefault = view()->shared('resource_url') ?: 'dashboard';

            $cancelHref = \Arr::get($cancelAttributes, 'href', $cancelHrefDefault);

            $buttons .= self::SPACER . $this->link(url($cancelHref), $cancelLabel, array_merge(['class' => 'btn btn-primary'], $cancelAttributes));
        }

        return $this->toHtmlString($this->formGroup($buttons, false, null, $wrapper_class));
    }

    public function customFields($model, $fieldClass = 'col-md-4')
    {
        // check if model has CustomFieldsModelTrait
        if (!method_exists($model, 'customFieldSettings')) {
            return '';
        }

        $customFields = $model->customFieldSettings();

        $fields = [];

        foreach ($customFields as $field) {
            $value = $model->exists ? $model->{$field->name} : $field->default_value;

            $fields [] = $this->handleCustomFieldInput($field, $value);
        }

        return renderContentInBSRows($fields, $fieldClass);
    }

    protected function handleCustomFieldInput($field, $value = null)
    {
        $input = '';
        $field = $this->parseSourceOptions($field, $value);
        switch ($field->type) {
            case 'label':
                $input = $this->{$field->type}($field->name, $field->label, $field->custom_attributes);
                break;
            case 'number':
            case 'date':
            case 'text':
            case 'textarea':
                $input = $this->{$field->type}($field->name, $field->label, $field->required, $value, $field->custom_attributes);
                break;
            case 'checkbox':
                $input = $this->{$field->type}($field->name, $field->label, $value, 1, $field->custom_attributes);
                break;
            case 'radio':
                $input = $this->{$field->type}($field->name, $field->label, $field->required, $field->options, $value, $field->custom_attributes);
                break;
            case 'select':
                $input = $this->{$field->type}($field->name, $field->label, $field->options, $field->required, $value, $field->custom_attributes, 'select2');
                break;
            case 'multi_values':
                $name = $field->name;

                if (!str_contains('[]', $name)) {
                    $name .= '[]';
                }
                $attributes = array_merge(['class' => 'select2-normal tags', 'multiple' => true], $field->custom_attributes);

                $input = $this->select($name, $field->label, $field->options, $field->required, $value, $attributes, 'select2');
                break;
        }

        return $input;
    }

    private function parseSourceOptions($field, $value)
    {
        if (isset($field->options_options['source']) && ($field->options_options['source'] == "database")) {
            switch ($field->type) {
                case 'checkbox':
                case 'radio':
                    $model = $field->options_options['source_model'];
                    $field->options = $model::all()->pluck($field->options_options['source_model_column'], 'id')->toArray();
                    break;
                case 'select':
                case 'multi_values':
                    $field->options = [];
                    $custom_attribues = [
                        ['data-model', $field->options_options['source_model']],
                        ['data-columns', json_encode([$field->options_options['source_model_column']])],

                        ['class', 'select2-ajax '],
                    ];

                    if ($value) {
                        $custom_attribues = array_merge($custom_attribues, [['data-selected', json_encode(is_array($value) ? $value : [$value])]]);
                    }
                    $field->custom_attributes = $custom_attribues;
                    break;

            }

        }

        return $field;

    }
}
