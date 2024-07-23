<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
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
        'profile_photo_path',
        'privileges',
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


    public function privilegesData(){

        if($this->role != 'admin'){
            $privileges = explode(',',$this->privileges);
            $module_parent_ids = Module::whereIn('id',$privileges)->pluck('parent_id')->toArray();
            $module_parent_ids1 = Module::whereIn('id',$privileges)->whereParentId(0)->pluck('id')->toArray();
            $parent_ids = array_merge($module_parent_ids,$module_parent_ids1);

            $menus = Module::where('parent_id',0)->whereIn('id',$parent_ids)->orderBy('sort','asc')->get();
        }else{
            $menus = Module::where('parent_id',0)->orderBy('sort','asc')->get();
        }
        return $menus;

    }

    public function submenuprivilegesData(){
        
        if($this->role != 'admin'){
            $menus = Module::where('parent_id','!=',0)->whereIn('id',explode(',',$this->privileges))->pluck('key')->toArray();
        }else{
            $menus = Module::where('parent_id','!=',0)->pluck('key')->toArray();
        }

        return $menus;
    }
}
