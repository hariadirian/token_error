<?php
//Prototypes Model modul admin developed by Dani Gunawan
// 31 may 2017

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Admin extends Model
{
    protected $table ='kmu_us_admin';
    protected $primaryKey = 'id';
    
    public $incrementing = false; //dipakai karena primaryKey id_user pada table admin tidak auto increment, increment tidak false maka akan eror pada table yang berelasi pada eloquent case ini digunakan untuk membuat Kode Rekam Medis fungsi ini digunakan untuk trigger

    public $timestamps = true; // jika false maka updated_at dan created_at tidak running

    protected $fillable = [
    'id',
    'code_admin',
    'nik',
    'telepon',
    'handphone',
    'email',
    'nama_admin',
    'jenis_kelamin',
    'gelar',
    'gambar_profiladmin',
    'tanggal_lahir',
    'negara_admin',
    'provinsi_admin',
    'kota_admin',
    'kecamatan_admin',
    'kelurahan_admin',
    'id_spesialisasi',
    'id_poliklinik',
    'status_admin',
    'alamat',
    'kodepos',
    'state'
    ];



    public function toUsers(){
        return $this->belongsTo(User::class,'code'); // admin milik si user
    }

    // public function kepemeriksaan(){
    //     return $this->hasMany(modul_pemeriksaan::class,'id_admin'); // one to many ya
    // }

    public function toCountries(){
        return $this->belongsTo(M_Negara::class, "negara_admin"); //negara milik si admin
    }

    public function toProvinces(){
        return $this->belongsTo(M_Provinsi::class,'provinsi_admin'); //provinsi milik si admin
    }

    public function toRegencies(){
        return $this->belongsTo(M_Kota::class,'kota_admin'); //kota milik si admin
    }

    public function toDistricts(){
        return $this->belongsTo(M_Kecamatan::class,'kecamatan_admin'); //kecamatan milik si admin
    }

    public function toVillages(){
        return $this->belongsTo(M_Kelurahan::class,'kelurahan_admin'); //kelurahan milik si admin
    }

    public function toPoliklinik(){
        return $this->belongsTo(M_Poliklinik::class,'id_poliklinik'); 
    }

}
