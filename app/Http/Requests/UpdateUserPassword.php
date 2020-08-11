<?php

namespace App\Http\Requests;

use App\Rules\PwdCurrent;
use Illuminate\Foundation\Http\FormRequest;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

class UpdateUserPassword extends FormRequest
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
            'current-password' => [new PwdCurrent],
            'new-password'     => PasswordRules::changePassword('current-password'),
        ];
    }
}
