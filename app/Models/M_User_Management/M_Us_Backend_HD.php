<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\DefaultTableAttribute;

class M_Us_Backend_HD extends Authenticatable
{
    use Notifiable, EntrustUserTrait, DefaultTableAttribute;

    protected $guard = 'administrator';

    protected $table = 'us_backend_hd';
    protected $primaryKey = 'id_us_backend_hd';
    public $timestamps = true;

    protected $fillable = [
        'cd_us_backend_hd',
        'username', 
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
        'log_roles', 
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function toUsBackendDt(){
        return $this->hasOne(M_Us_Backend_DT::class, 'id_us_backend_hd');
    }
    public function toUsBackendOrganizationUser(){
        return $this->hasMany(M_Us_Backend_Organization_User::class, 'id_us_backend_hd');
    }
}
