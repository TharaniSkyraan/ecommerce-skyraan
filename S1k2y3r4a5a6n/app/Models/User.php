<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\ResetPassword;
use Mail;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'signup_by',
        'subscription'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    public function sendPasswordResetNotification($token)
    {
        return Mail::send(new ResetPassword($token, $this->email, $this->name));
    }
        
    public function usercart()
    {
        return $this->hasOne(UserCart::class, 'user_id', 'id');
    }
        
    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }
        
    public function address()
    {
        return $this->hasOne(SavedAddress::class, 'user_id', 'id')->orderByRaw("is_default = 'yes' DESC");
    }
        
    public function addresses()
    {
        return $this->hasMany(SavedAddress::class, 'user_id', 'id')->orderByRaw("is_default = 'yes' DESC");
    }
}
