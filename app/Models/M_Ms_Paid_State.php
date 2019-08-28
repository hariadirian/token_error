<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\FrontendTableAttribute;

class M_Ms_Paid_State extends Model
{
    use FrontendTableAttribute;
    
    protected $table ='tmii_ms_paid_state';
    protected $primaryKey = 'id_ms_paid_state';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'paid_state_name',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

}
