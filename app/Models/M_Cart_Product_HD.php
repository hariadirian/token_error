<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\M_User_Management\M_Us_Frontend_HD;
use DB;
use App\Traits\FrontendTableAttribute;

class M_Cart_Product_HD extends Model
{
    use FrontendTableAttribute;

    protected $table ='tmii_et_cart_product_hd';
    protected $primaryKey = 'id_et_cart_product_hd';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_et_cart_product_hd',
        'id_us_frontend_hd',
        'payment_method',
        'bank_name',
        'account_name',
        'cc_number',
        'cc_expired_date',
        'cc_cvv',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'done_at',
        'done_by',
        'is_done',
        'is_active',
    ];

    public function toCartProductBd(){
        return $this->hasMany(M_Cart_Product_BD::class, 'id_et_cart_product_hd');
    }

    public function toUsFrontendHd(){
        return $this->belongsTo(M_Us_Frontend_HD::class, 'id_us_frontend_hd');
    }

    public function summaryCartHd($id_et_cart_product_hd){

        return $this->
            leftJoin('tmii_et_cart_product_bd as b', 
                'tmii_et_cart_product_hd.id_et_cart_product_hd', '=', 'b.id_et_cart_product_hd')->
            leftJoin(DB::raw('(select count(id_et_cart_product_dt) as count_cart_dt, id_et_cart_product_bd from tmii_et_cart_product_dt where is_active = \'Y\' and state = \'Y\' group by id_et_cart_product_bd) as c'), 
                'b.id_et_cart_product_bd',                       '=', 'c.id_et_cart_product_bd')->
            where('tmii_et_cart_product_hd.id_et_cart_product_hd', $id_et_cart_product_hd)->
            where('tmii_et_cart_product_hd.is_active', 'Y')->
            where('tmii_et_cart_product_hd.state', 'Y')->
            where('b.is_active', 'Y')->
            where('b.state', 'Y')->
            select(
                'tmii_et_cart_product_hd.id_et_cart_product_hd', 
                DB::raw('sum(b.total_amount) AS total_amount'), 
                DB::raw('sum(b.qty_product) AS qty_ticket'), 
                DB::raw('count(distinct (b.id_et_cart_product_bd )) AS qty_product'),
                DB::raw('count(distinct (case when b.ticket_type = \'PROMOTION\' then b.id_et_cart_product_bd else null end)) as qty_promo'),
                DB::raw('count(distinct (case when b.ticket_type = \'PRODUCT\' then b.id_et_cart_product_bd else null end)) as qty_regular'),
                DB::raw('sum(c.count_cart_dt) as sum_qty_product')
            )->
            groupBy('tmii_et_cart_product_hd.id_et_cart_product_hd');

    }
    // public function boundToSummaryCartBd(){
    //     return $this->belongsTo(
    //         M_Cart_Product_BD::class, 'summaryCartBd', 'id_et_cart_product_hd', 'id_et_cart_product_hd');
    // }
        
}
