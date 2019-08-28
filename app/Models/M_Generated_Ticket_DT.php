<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\FrontendTableAttribute;

class M_Generated_Ticket_DT extends Model
{
    use FrontendTableAttribute;
    
    protected $table ='tmii_et_generated_ticket_dt';
    protected $primaryKey = 'id_et_generated_ticket_dt';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_generated_ticket_dt',
        'id_et_generated_ticket_hd',
        'id_et_cart_product_dt',
        'cd_generated_batch',
        'total_generated',
        'qty',
        'created_by',
        'generated_at',
        'generated_by',
        'deleted_at',
        'deleted_by',
        'is_active'
    ];

    public function toGeneratedTicketHd(){
        return $this->belongsTo(M_Generated_Ticket_HD::class, 'id_et_generated_ticket_hd');
    }
    public function toCartProductDt(){
        return $this->belongsTo(M_Cart_Product_DT::class, 'id_et_cart_product_dt');
    }
    public function toRedeemedTicket(){
        return $this->hasMany(M_Redeemed_Ticket::class, 'id_et_generated_ticket_dt');
    }
}
