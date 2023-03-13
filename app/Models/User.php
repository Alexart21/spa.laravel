<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Support\Facades\Auth;


// когда имплементируем MustVerifyEmail то при OAuth при удачной авторизации перебрасывает на страницу с сообщением
// о необходимости верифицировать email
// что не есть god надо найти этот редирект и как то пофиксить

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /*protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
    ];*/

    /*public function sendEmailVerificationNotification()
    {
//        die('kjkjkjkj');
    }*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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
}
