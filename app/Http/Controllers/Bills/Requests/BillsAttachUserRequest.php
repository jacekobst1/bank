<?php

namespace App\Http\Controllers\Bills\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillsAttachUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'           => 'required|numeric|exists:users,id',
            'bill_number'       => 'required|string|size:26|exists:bills,number',
        ];
    }

    public function messages()
    {
        return [
            'bill_number.exists' => __('The bill number must exists in our database')
        ];
    }
}
