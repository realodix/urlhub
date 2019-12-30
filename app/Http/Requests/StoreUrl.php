<?php

namespace App\Http\Requests;

use App\Rules\URL\UrlBlacklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Url;

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
        $url = Auth::check() ? Url::whereUrlKey($this->input('custom_url_key'))->whereUserId(Auth::id())->first() : null;
        return [
            'long_url'       => ['required', 'url', 'max:65535', new UrlBlacklist],
            'custom_url_key' => [
                'nullable',
                'max:20',
                'alpha_dash',
                ($url)
                    ? 'unique:urls,url_key,' . $url->id
                    : 'unique:urls,url_key'
            ],
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
            'long_url.required'     => __('Must be filled, should not be empty.'),
            'long_url.url'          => __('Incorrect link format. The link must begin "http://" or "https://".'),
            'custom_url_key.max'    => __('The custom url may not be greater than :max characters.'),
            'custom_url_key.unique' => __(':input has already been taken'),
        ];
    }
}
