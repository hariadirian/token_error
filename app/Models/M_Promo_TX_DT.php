<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class M_Promo_TX_DT extends Model
{
    protected $table ='tmii_et_promo_tx_dt';
    protected $primaryKey = 'id_et_promo_tx_dt';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_promo_tx_dt',
        'id_et_promo_tx_hd',
        'category',
        'cd_promo_ref',
        'type_promo',
        'min_val_promo',
        'amount_promo',
        'product_promo',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toPromoTxHd(){
        return $this->belongsTo(M_Promo_TX_HD::class, 'id_et_promo_tx_hd');
    }
}
