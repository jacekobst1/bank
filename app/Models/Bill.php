<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    private $currency_types = [
        1 => 'PLN'
    ];

    public function users() { return $this->belongsToMany('App\Models\User', 'bill_user_pivot'); }
}
