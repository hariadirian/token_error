<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Promo_TX_HD extends Model
{
    protected $table ='tmii_et_promo_tx_hd';
    protected $primaryKey = 'id_et_promo_tx_hd';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_promo_tx_hd',
        'id_et_cart_product_bd',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toCartProductBD(){
        return $this->belongsTo(M_Cart_Product_BD::class, 'id_et_cart_product_bd');
    }

    public function toPromoTxDt(){
        return $this->hasMany(M_Promo_TX_DT::class, 'id_et_promo_tx_hd');
    }
}
