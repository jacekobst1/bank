<?php

namespace App\Http\Controllers\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasAnyPermission(['manage-bills', 'manage-settings']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'          => 'required|string',
            'password_verify'   => 'required|string|same:password',
        ];
    }
}
