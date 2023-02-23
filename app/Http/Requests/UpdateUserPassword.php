<?php

namespace App\Http\Requests;

use App\Rules\PwdCurrent;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current-password' => [new PwdCurrent],
            'new-password'     => ['required', 'min:6', 'confirmed', 'unique:users,password', 'different:current-password'],
        ];
    }
}
