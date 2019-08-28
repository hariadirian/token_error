<?php

namespace App\Models\M_Idempiere;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class M_Ad_User extends Authenticatable
{

    use Notifiable, EntrustUserTrait;
    
    protected $connection = 'idem_db';

    protected $table = 'adempiere.ad_user';
    protected $primarykey = 'ad_user_id';

    public $timestamps = true;
    public $incrementing = false;
    protected $fillable = [
        'ad_user_id',
        'ad_client_id',
        'ad_org_id',
        'isactive',
        'created',
        'created_by',
        'name',
        'value',
        'password',
        'email',
        'c_bpartner_location_id',
        'ad_user_uu',
        'islocked'
    ];



    // public function toUsers(){
    //     return $this->belongsTo(User::class,'code'); // admin milik si user
    // }


    // public function toCountries(){
    //     return $this->belongsTo(M_Negara::class, "negara_admin"); //negara milik si admin
    // }

    // public function toProvinces(){
    //     return $this->belongsTo(M_Provinsi::class,'provinsi_admin'); //provinsi milik si admin
    // }

    // public function toRegencies(){
    //     return $this->belongsTo(M_Kota::class,'kota_admin'); //kota milik si admin
    // }

    // public function toDistricts(){
    //     return $this->belongsTo(M_Kecamatan::class,'kecamatan_admin'); //kecamatan milik si admin
    // }

    // public function toVillages(){
    //     return $this->belongsTo(M_Kelurahan::class,'kelurahan_admin'); //kelurahan milik si admin
    // }

    // public function toPoliklinik(){
    //     return $this->belongsTo(M_Poliklinik::class,'id_poliklinik'); 
    // }

}
