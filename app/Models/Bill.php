<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    private static $currency_types = [
        1 => 'PLN'
    ];
    protected $appends = [
        'formatted_number',
        'balance',
        'currency'
    ];

    public function users() { return $this->belongsToMany('App\Models\User', 'bill_user_pivot'); }
    public function sourceTransactions() { return $this->hasMany('App\Models\Transaction', 'source_bill_id'); }
    public function targetTransactions() { return $this->hasMany('App\Models\Transaction', 'target_bill_id'); }

    public function getCurrencyAttribute()
    {
        return self::$currency_types[$this->currency_type_id];
    }
    public function getBalanceAttribute()
    {
        return
            $this->targetTransactions->sum('amount')
            - $this->sourceTransactions->sum('amount');
    }
    public function getFormattedNumberAttribute()
    {
        $number_parts = str_split($this->number);
        $number = $number_parts[0].$number_parts[1].' ';
        for ($i=1; $i<25;) {
            $number
                .= $number_parts[++$i]
                .= $number_parts[++$i]
                .= $number_parts[++$i]
                .= $number_parts[++$i]
                .= ' ';
        }
        return $number;
    }
}
