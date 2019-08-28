<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\FrontendTableAttribute;

class M_Generated_Ticket_HD extends Model
{
    
    use FrontendTableAttribute;
    
    protected $table ='tmii_et_generated_ticket_hd';
    protected $primaryKey = 'id_et_generated_ticket_hd';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_generated_ticket_hd',
        'id_et_ordered_ticket_txes',
        'file_path',
        'email_sent_at',
        'total_downloaded',
        'created_by',
        'created_at'
    ];

    public function toOrderedTicketTxes(){
        return $this->belongsTo(M_Ordered_Ticket_Txes::class, 'id_et_ordered_ticket_txes');
    }
    public function toGeneratedTicketDt(){
        return $this->hasMany(M_Generated_Ticket_DT::class, 'id_et_generated_ticket_hd');
    }
}
