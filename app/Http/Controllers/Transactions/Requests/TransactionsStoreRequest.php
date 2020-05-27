<?php

namespace App\Http\Controllers\Transactions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('manage-bill');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bill_id'                   => 'required|numeric|exists:bills,id',
            'target_bill_number'        => 'required|string|size:26|exists:bills,number',
            'amount'                    => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'target_bill_number.exists' => __('The bill number must exists in our database')
        ];
    }
}
