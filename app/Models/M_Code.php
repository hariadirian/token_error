<?php
//Prototypes Model modul admin developed by Dani Gunawan
// 31 may 2017

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Code extends Model
{
    protected $table ='tmii_ms_code';
    protected $primaryKey = 'id';
    
    public $incrementing = true; //dipakai karena primaryKey id_user pada table admin tidak auto increment, increment tidak false maka akan eror pada table yang berelasi pada eloquent case ini digunakan untuk membuat Kode Rekam Medis fungsi ini digunakan untuk trigger

    public $timestamps = true; // jika false maka updated_at dan created_at tidak running

    protected $fillable = [
    'id',
    'tabletype',
    'prefix',
    ];

}
