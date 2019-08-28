<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\M_User_Management\M_Us_Backend_Organizations;
use App\Traits\FrontendTableAttribute;

class M_Cart_Product_DT extends Model
{
    use FrontendTableAttribute;

    protected $table ='tmii_et_cart_product_dt';
    protected $primaryKey = 'id_et_cart_product_dt';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_cart_product_dt',
        'id_et_cart_product_bd',
        'm_product_uu',
        'm_attributeset_uu',
        'ticket_name',
        'amount',
        'qty_ticket',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

    public function toCartProductBd(){
        return $this->belongsTo(M_Cart_Product_BD::class, 'id_et_cart_product_bd');
    }
    public function toBackendOrganization(){
        return $this->hasMany(M_Us_Backend_Organizations::class, 'm_attributeset_uu', 'm_attributeset_uu');
    }

}
