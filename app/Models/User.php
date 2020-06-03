<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password',];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bills() { return $this->belongsToMany('App\Models\Bill', 'bill_user_pivot'); }
    public function cards() { return $this->hasMany('App\Models\Card'); }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }
    public function getZipCodeAttribute($value)
    {
        return substr($value, 0, 2).'-'.substr($value, 2, 3);
    }
}
