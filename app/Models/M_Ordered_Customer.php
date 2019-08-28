<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Ordered_Customer extends Model
{
    protected $table ='tmii_ordered_customer';
    protected $primaryKey = 'ordered_customer_id';
    
    public $incrementing = true; //dipakai karena primaryKey id_user pada table admin tidak auto increment, increment tidak false maka akan eror pada table yang berelasi pada eloquent case ini digunakan untuk membuat Kode Rekam Medis fungsi ini digunakan untuk trigger

    public $timestamps = false; // jika false maka updated_at dan created_at tidak running

    protected $fillable = [
        'ordered_customer_id',
        'no_order',
        'cp_ktp',
        'cp_nama_lengkap',
        'cp_no_hp',
        'cp_email',
        'instansi',
        'alamat',
        'tanggal_tiket',
        'kuota',
        'nama_pentransfer',
        'nama_bank',
        'product_id',
        'created_by',
    ];

    public function toProduct(){
        return $this->belongsTo(M_Product::class, 'product_id'); // admin milik si user
    }

}
