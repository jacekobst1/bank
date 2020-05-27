<?php

namespace App\Http\Controllers\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'           => 'required|min:8',
            'password_verify'    => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password_verify.same' => __('The passwords are different')
        ];
    }
}
