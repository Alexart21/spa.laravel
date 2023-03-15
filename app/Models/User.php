<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;


// когда имплементируем MustVerifyEmail то при OAuth при удачной авторизации перебрасывает на страницу с сообщением
// о необходимости верифицировать email
// что не есть god надо найти этот редирект и как то пофиксить

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $appends = [
        'profile_photo_url',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function oauth()
    {
        // реализация связи один к одному
        // в таблице куда ссылается hasOne(а это у нас oauth) должно быть поле user_id
        return $this->hasOne(Oauth::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
