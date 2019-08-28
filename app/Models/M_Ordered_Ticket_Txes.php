<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\FrontendTableAttribute;

class M_Ordered_Ticket_Txes extends Model
{
    use FrontendTableAttribute;
    
    protected $table ='tmii_et_ordered_ticket_txes';
    protected $primaryKey = 'id_et_ordered_ticket_txes';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_ordered_ticket_txes',
        'id_et_cart_product_hd',
        'total_amount',
        'paid_at',
        'paid_state',
        'email_sent_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toCartProductHd(){
        return $this->belongsTo(M_Cart_Product_HD::class, 'id_et_cart_product_hd');
    }
    public function toGenerateTicketHd(){
        return $this->belongsTo(M_Generated_Ticket_HD::class, 'id_et_ordered_ticket_txes', 'id_et_ordered_ticket_txes');
    }
    public function toMsPaidState(){
        return $this->hasOne(M_Ms_Paid_State::class, 'id_ms_paid_state', 'paid_state');
    }
}
