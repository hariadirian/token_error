<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Log_Login extends Model
{
    protected $table = 'kmu_ms_log_login';
    protected $primarykey = 'code_ms_log_login';

    public $timestamps = false;

    protected $fillable = [
        'code_ms_log_login',
        'code_user',
        'user_agent',
        'ip',
        'client_ip',
        'created_by',
        'created_at'
    ];

    public function touser(){
        return $this->belongsTo(User::class,'code_user', 'code_user'); // dokter milik si user
    }
}
