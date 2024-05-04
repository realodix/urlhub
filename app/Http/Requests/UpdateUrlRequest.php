<?php

namespace App\Http\Requests;

use App\Models\Url;
use App\Rules\NotBlacklistedDomain;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $titleLength = Url::TITLE_LENGTH;

        return [
            'title'    => ["max:{$titleLength}"],
            'long_url' => ['required', 'url', 'max:65535', new NotBlacklistedDomain],
        ];
    }
}
