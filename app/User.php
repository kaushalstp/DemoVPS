<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{

    use Notifiable;
    protected $table = 'tbl_registration';
    protected $primaryKey = 'reg_id';

    protected $fillable = [
         'name', 'email', 'password',
    ];
 
    protected $hidden = [
         'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
 
    public function getJWTIdentifier() {
        return $this->getKey();
    }
 
    public function getJWTCustomClaims() {
        return [];
    }  

    public function getAuthPassword()
    {
        return $this->password;
    }

}
