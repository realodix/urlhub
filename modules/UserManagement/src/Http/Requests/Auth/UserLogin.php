<?php

namespace UrlHub\UserManagement\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserLogin extends FormRequest
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
        $username = config('laravel_user_management.auth.username');

        return [
            "$username"     => "required" . ($username == 'mobile' ? '|numeric' : '|email'),
            'password'      => 'required',
        ];
    }
}
