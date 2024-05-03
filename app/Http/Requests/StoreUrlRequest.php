<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumHyphen;
use App\Rules\Url\DomainBlacklist;
use Illuminate\Foundation\Http\FormRequest;

class StoreUrlRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'long_url'   => ['required', 'url', 'max:65535', new DomainBlacklist],
            'custom_key' => ['nullable', 'max:20', new AlphaNumHyphen, 'unique:urls,keyword'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'long_url.required' => __('The URL field must be filled, should not be empty.'),
            'custom_key.max'    => __('The custom url may not be greater than :max characters.'),
            'custom_key.unique' => __(':input has already been taken'),
        ];
    }
}
