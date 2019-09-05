<?php

namespace Mekaeil\LaravelUserManagement\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRole extends FormRequest
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
            'name'          => "required|unique:".$tableNames['roles'].",name," . $this->ID,
            'title'         => 'required|string',
            'guard_name'    => 'nullable',
            'description'   => 'nullable',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'nullable|exists:'. $tableNames['permissions']. ',name',
        ];
    }
}
