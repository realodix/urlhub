<?php

namespace UrlHub\UserManagement\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePermission extends FormRequest
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
        $tableNames = config('permission.table_names');

        return [
            'name'          => 'required|unique:'. $tableNames['permissions'],
            'title'         => 'required|string',
            'module'        => 'nullable',
            'guard_name'    => 'nullable',
            'description'   => 'nullable',
        ];
    }
}
