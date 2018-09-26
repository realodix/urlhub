<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUrl extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'long_url'          => 'required|url|max:65535',
            'short_url_custom'  => 'nullable|max:20|alpha_dash|unique:urls',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'long_url.required'         => __('Must be filled, should not be empty.'),
            'long_url.url'              => __('Incorrect link format. The link must begin "http://" or "https://".'),
            'short_url_custom.max'      => __('The custom url may not be greater than :max characters.'),
            'short_url_custom.unique'   => __(':input has already been taken'),
        ];
    }
}
