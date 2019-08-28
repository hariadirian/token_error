<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\DefaultTableAttribute;

class M_Redeemed_Ticket extends Model
{
    use DefaultTableAttribute;
    
    protected $table ='tmii_et_redeemed_ticket';
    protected $primaryKey = 'id_et_redeemed_ticket';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_redeemed_ticket',
        'id_et_generated_ticket_dt',
        'qty_redeemed',
        'created_by',
        'deleted_at',
        'deleted_by',
        'is_active'
    ];

    public function toGeneratedTicketDt(){
        return $this->hasOne(M_Generated_Ticket_DT::class, 'id_et_generated_ticket_dt');
    }
}
