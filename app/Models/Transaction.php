<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    private static $types = [
        1 => 'Bank transfer',
        2 => 'ATM'
    ];

    public function sourceBill() { return $this->belongsTo('App\Models\Bill', 'source_bill_id'); }
    public function targetBill() { return $this->belongsTo('App\Models\Bill', 'target_bill_id'); }


    public function getTypeAttribute()
    {
        return __(self::$types[$this->type_id]);
    }
}
