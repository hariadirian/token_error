<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class M_Us_Frontend_DT extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    protected $table = 'us_frontend_dt';
    protected $primaryKey = 'id_us_frontend_dt';
    public $timestamps = false; 

    protected $fillable = [
        'cd_us_frontend_dt', 
        'id_us_frontend_hd', 
        'first_name', 
        'last_name', 
        'mobile_phone', 
        'address', 
        'institute', 
        'id_card', 
        'created_by',
        'updated_at', 
        'updated_by', 
        'state', 
    ];

    public function toUsFrontendHd(){
        return $this->belongsTo(M_Us_Frontend_HD::class, 'id_us_frontend_hd');
    }
}
