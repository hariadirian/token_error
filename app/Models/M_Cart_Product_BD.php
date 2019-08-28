<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\FrontendTableAttribute;

class M_Cart_Product_BD extends Model
{
    use FrontendTableAttribute;

    protected $table ='tmii_et_cart_product_bd';
    protected $primaryKey = 'id_et_cart_product_bd';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_cart_product_bd',
        'id_et_cart_product_hd',
        'cd_product_ref',
        'product_name',
        'ticket_type',
        'ticket_date',
        'qty_product',
        'total_amount',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toCartProductDt(){
        return $this->hasMany(M_Cart_Product_DT::class, 'id_et_cart_product_bd');
    }

    public function toPromoTXHD(){
        return $this->hasMany(M_Promo_TX_HD::class, 'id_et_cart_product_bd');
    }

    public function toCartProductHd(){
        return $this->hasOne(M_Cart_Product_HD::class, 'id_et_cart_product_hd');
    }

    public function toTicketImgHd(){
        return $this->hasOne(M_Ticket_Img_HD::class, 'idem_ticket_uu', 'cd_product_ref');
    }
}
