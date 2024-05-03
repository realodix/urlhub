<?php

namespace App\Http\Requests;

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
        $minLen = config('urlhub.custom_keyword_min_length');
        $maxLen = config('urlhub.custom_keyword_max_length');

        return [
            'long_url'   => ['required', 'url', 'max:65535', new DomainBlacklist],
            'custom_key' => [
                "min:$minLen", "max:$maxLen", 'unique:App\Models\Url', 'lowercase:field',
                new \App\Rules\AlphaNumHyphen,
                new \App\Rules\Url\KeywordBlacklist,
            ],
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
