<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\DefaultTableAttribute;

class M_Us_Backend_Organizations extends Authenticatable
{
    use DefaultTableAttribute;

    protected $guard = 'administrator';

    protected $table = 'us_backend_organizations';
    protected $primaryKey = 'id_us_backend_organizations';
    public $timestamps = true;

    protected $fillable = [
        'cd_us_backend_organization',
        'organization_name', 
        'organization_type', 
        'description',  
        'm_attributeset_uu',  
        'created_by', 
        'updated_at', 
        'updated_by', 
        'deleted_at', 
        'deleted_by', 
        'is_active', 
    ];

    public function toUsBackendOrganizationUser(){
        return $this->hasMany(M_Us_Backend_Organization_User::class, 'id_us_backend_organization');
    }
}
