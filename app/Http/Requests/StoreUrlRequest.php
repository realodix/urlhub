<?php

namespace App\Http\Requests;

use App\Rules\LinkRules;
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
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'dest_android' => 'Android link',
            'dest_ios' => 'iOS link',
            'expires_at' => 'expiration date',
            'expired_notes' => 'expiration notes',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'custom_key' => ['nullable', ...LinkRules::customKeyword()],
            'long_url' => ['required', ...LinkRules::link()],
            'dest_android' => ['nullable', ...LinkRules::link()],
            'dest_ios' => ['nullable', ...LinkRules::link()],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'expired_clicks' => ['nullable', 'integer', 'min:0'],
            'expired_url' => ['nullable', ...LinkRules::link()],
            'expired_notes' => ['nullable', 'max:200'],
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
            'long_url.required' => 'The URL field must be filled, should not be empty.',
            'expires_at.after' => 'The :attribute must be a future date and time.',
            'custom_key.max' => 'The custom url may not be greater than :max characters.',
            'custom_key.unique' => ':input has already been taken',
        ];
    }
}
