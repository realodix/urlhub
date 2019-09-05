<?php

namespace Mekaeil\LaravelUserManagement\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermission extends FormRequest
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
            'name'          => 'required|unique:'. $tableNames['permissions'] .',name,' . $this->ID,
            'title'         => 'required|string',
            'module'        => 'nullable',
            'guard_name'    => 'nullable',
            'description'   => 'nullable',
        ];
    }
}
