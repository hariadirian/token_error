<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Product extends Model
{
    protected $table ='tmii_temp_produk';
    protected $primaryKey = 'id';
    
    public $incrementing = true; //dipakai karena primaryKey id_user pada table admin tidak auto increment, increment tidak false maka akan eror pada table yang berelasi pada eloquent case ini digunakan untuk membuat Kode Rekam Medis fungsi ini digunakan untuk trigger

    public $timestamps = false; // jika false maka updated_at dan created_at tidak running

    protected $fillable = [
        'id',
        'produk',
        'harga',
        'tipe',
        'min_qty',
        'max_qty'
    ];

}
