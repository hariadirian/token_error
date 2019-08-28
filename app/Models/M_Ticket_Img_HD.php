<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Ticket_Img_Hd extends Model
{
    protected $connection = 'mysql';
    protected $table ='tmii_ms_ticket_img_hd';
    protected $primaryKey = 'id_ms_ticket_img_hd';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_ms_ticket_img_hd',
        'idem_ticket_uu',
        'type_ticket',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toTicketImgDt(){
        return $this->hasMany(M_Ticket_Img_DT::class, 'id_ms_ticket_img_hd');
    }
}
