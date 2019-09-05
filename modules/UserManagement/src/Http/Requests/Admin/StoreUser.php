<?php

namespace UrlHub\UserManagement\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
        $userTable       = config("laravel_user_management.users_table");
        $departmentTable = config("laravel_user_management.user_department_table");
        $tableNames      = config('permission.table_names');

        return [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => "nullable|email|unique:$userTable,email",
            'mobile'        => "required|unique:$userTable,mobile",
            'password'      => 'required|min:6',
            'roles'         => 'nullable|array',
            'roles.*'       => 'nullable|exists:'. $tableNames['roles']. ',name',
            'departments'   => 'nullable|array',
            'departments.*' => "nullable|exists:$departmentTable,id",
        ];
    }
}
