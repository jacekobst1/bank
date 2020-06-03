<?php

namespace App\Http\Controllers\Bills\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillsDetachUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'           => 'required|numeric|exists:users,id'
        ];
    }
}
