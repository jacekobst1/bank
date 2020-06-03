<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['active'];
    protected $appends = [
        'type',
        'formatted_number',
    ];

    private static $types = [
        1 => 'normal',
        2 => 'silver',
        3 => 'gold'
    ];


    public function user() { return $this->belongsTo('App\Models\User'); }
    public function bill() { return $this->belongsTo('App\Models\Bill'); }

    public function getTypeAttribute() {
        return __(self::$types[$this->type_id]);
    }
    public function getFormattedNumberAttribute()
    {
        $number_parts = str_split($this->number);
        $number = '';
        for ($i=0; $i<16;) {
            $number
                .= $number_parts[$i++]
                .= $number_parts[$i++]
                .= $number_parts[$i++]
                .= $number_parts[$i++]
                .= ' ';
        }
        return $number;
    }
}
