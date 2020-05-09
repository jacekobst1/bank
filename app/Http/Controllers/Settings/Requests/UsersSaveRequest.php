<?php

namespace App\Http\Controllers\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('manage-settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'pesel'             => 'required|digits:11',
            'email'             => 'required|email',
            'address'           => 'required|string',
            'city'              => 'required|string',
            'zip_code'          => [
                                    'required',
                                    'string',
                                    'regex:/\d{2}-\d{3}/',
                                ],
            'role_id'            => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'zip_code.regex' => __('The zip code format is invalid (xx-xxx).')
        ];
    }
}
