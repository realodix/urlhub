<?php

namespace UrlHub\UserManagement\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistration extends FormRequest
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
        $username   = config('laravel_user_management.auth.username');
        $userTable  = config("laravel_user_management.users_table");

        return [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            "$username"     => "required" . ($username == 'mobile' ? "|unique:$userTable,mobile" : "|email|unique:$userTable,email"),
            'password'      => 'required|confirmed|min:6',
        ];
    }
}
