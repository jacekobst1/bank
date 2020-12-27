<?php

namespace App\Http\Controllers\Transactions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionsGenerateReport extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bill_id'       => 'required|numeric|exists:bills,id',
            'start_date'    => 'required|date|before_or_equal:end_date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ];
    }
}
