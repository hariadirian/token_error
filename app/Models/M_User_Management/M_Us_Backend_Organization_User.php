<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\DefaultTableAttribute;

class M_Us_Backend_Organization_User extends Authenticatable
{
    use DefaultTableAttribute;

    protected $guard = 'administrator';

    protected $table = 'us_backend_organization_user';
    // protected $primaryKey = 'id_us_backend_organizations';
    public $timestamps = false;

    protected $fillable = [
        'id_us_backend_hd',
        'id_us_backend_organization',
        'created_by',
        'deleted_by',
        'is_active'
    ];

    public function toUsBackendOrganizations(){
        return $this->hasOne(M_Us_Backend_Organizations::class, 'id_us_backend_organization', 'id_us_backend_organization');
    }

    public function toUsBackendHd(){
        return $this->belongsTo(M_Us_Backend_HD::class, 'id_us_backend_hd');
    }
}
