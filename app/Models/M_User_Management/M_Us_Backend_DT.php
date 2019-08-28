<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\DefaultTableAttribute;

class M_Us_Backend_DT extends Authenticatable
{
    use Notifiable, EntrustUserTrait, DefaultTableAttribute;

    protected $table = 'us_backend_dt';
    protected $primaryKey = 'id_us_backend_dt';
    public $timestamps = false; 

    protected $fillable = [
        'cd_us_backend_dt', 
        'id_us_backend_hd', 
        'nip', 
        'first_name', 
        'last_name', 
        'id_card', 
        'm_product_uu', 
        'mobile_phone', 
        'address', 
        'created_by',
        'updated_at', 
        'updated_by', 
        'deleted_at', 
        'deleted_by', 
        'is_active', 
    ];

    public function toUsBackendHd(){
        return $this->belongsTo(M_Us_Backend_HD::class, 'id_us_backend_hd');
    }
}
