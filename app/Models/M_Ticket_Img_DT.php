<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Ticket_Img_DT extends Model
{
    protected $table ='tmii_ms_ticket_img_dt';
    protected $primaryKey = 'id_ms_ticket_img_dt';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_ms_ticket_img_dt',
        'id_ms_ticket_img_hd',
        'filename',
        'srcname',
        'img_type',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toTicketImgHd(){
        return $this->belongsTo(M_Ticket_Img_HD::class, 'id_ms_ticket_img_hd');
    }
}
