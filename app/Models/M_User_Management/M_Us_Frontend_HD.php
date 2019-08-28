<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class M_Us_Frontend_HD extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    protected $guard = 'customer';

    protected $table = 'us_frontend_hd';
    protected $primaryKey = 'id_us_frontend_hd';
    public $timestamps = true;

    protected $fillable = [
        'cd_us_frontend_hd',
        'email', 
        'password',  
        'registered_token', 
        'actived_at',
        'remember_token',
        'created_by', 
        'updated_at', 
        'updated_by', 
        'deleted_at', 
        'deleted_by', 
        'is_active',
        'provider',
        'google_id',
        'facebook_id',
        'twitter_id' 
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function toUsFrontendDt(){
        return $this->hasMany(M_Us_Frontend_DT::class, 'id_us_frontend_hd');
    }
}
