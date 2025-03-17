<?php

namespace App\Http\Requests;

use App\Rules\NotBlacklistedDomain;
use Illuminate\Foundation\Http\FormRequest;

class StoreUrlRequest extends FormRequest
{
    /** @var int */
    const URL_LENGTH = 7000;

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
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'dest_android' => 'Android link',
            'dest_ios' => 'iOS link',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $settings = app(\App\Settings\GeneralSettings::class);
        $maxUrlLen = self::URL_LENGTH;
        $minLen = $settings->custom_keyword_min_length;
        $maxLen = $settings->custom_keyword_max_length;

        return [
            'long_url' => [
                'required', "max:{$maxUrlLen}", new NotBlacklistedDomain,
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:\/\/[^\s]+$/', $value)) {
                        $fail('The :attribute field must be a valid URL or a valid deeplink.');
                    }
                },
            ],
            'dest_android' => [
                'nullable', "max:{$maxUrlLen}", new NotBlacklistedDomain,
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:\/\/[^\s]+$/', $value)) {
                        $fail('The :attribute field must be a valid URL or a valid deeplink.');
                    }
                },
            ],
            'dest_ios' => [
                'nullable', "max:{$maxUrlLen}", new NotBlacklistedDomain,
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:\/\/[^\s]+$/', $value)) {
                        $fail('The :attribute field must be a valid URL or a valid deeplink.');
                    }
                },
            ],
            'custom_key' => [
                'nullable', 'unique:urls,keyword',
                "min:{$minLen}", "max:{$maxLen}", 'lowercase',
                new \App\Rules\AlphaNumHyphen,
                new \App\Rules\NotBlacklistedKeyword,
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
