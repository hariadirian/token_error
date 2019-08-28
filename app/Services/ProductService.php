<?php
namespace App\Services;

use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Idempiere\M_View\MV_Product;
use App\Models\M_Generated_Ticket_DT;
use DB;
  
class ProductService
{
    public function regularActive($cd = null, $popular = null){

        $regular = MV_Product::with(['toTicketImgHd' => function($query1) {
            $query1->where('tmii_ms_ticket_img_hd.type_ticket', 'PRODUCT');
            $query1->where('tmii_ms_ticket_img_hd.is_active', 'Y');
            $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
            $query1->with(['toTicketImgDt' => function($query2){
                $query2->where('is_active', 'Y');
                $query2->where('state', 'Y');
            }]);
        }])->where('isactive', 'Y')->where('iswebstorefeatured', 'Y');

        if($cd){
            return $regular->where('m_product_uu', $cd)->first();
        }
        
        if ($popular == 'popular') {
            $popular = M_Generated_Ticket_DT::leftJoin('tmii_et_cart_product_dt as b', 'tmii_et_generated_ticket_dt.id_et_cart_product_dt', '=', 'b.id_et_cart_product_dt')->select(
                DB::raw("SUM(tmii_et_generated_ticket_dt.qty) as qty_ticket_sum"), 
                'm_product_uu'
            )->where('tmii_et_generated_ticket_dt.state', 'Y')->where('tmii_et_generated_ticket_dt.is_active' , 'Y')
            ->groupBy('m_product_uu')
            ->orderBy('qty_ticket_sum', 'DESC')
            ->pluck('m_product_uu');

            if($popular)
                return $regular->whereIn('m_product_uu', $popular)->get();     
        }   
        return $regular->orderBy('created', 'DESC')->get();     

    }

    public function promotionActive($cd = null, $popular = null){

        $promo = MV_Promo::with(['toTicketImgHd' => function($query1){
            $query1->where('type_ticket', 'PROMOTION');
            $query1->where('is_active', 'Y');
            $query1->where('state', 'Y');
            $query1->with(['toTicketImgDt' => function($query2){
                $query2->where('is_active', 'Y');
                $query2->where('state', 'Y');
            }]);
        }])->where('isactive', 'Y');
        if($cd){
            return $promo->where('m_promotion_uu', $cd)->first();
        }

        return $promo->orderBy('created', 'DESC')->get();

    }
    public function regularRandomActive(){

        return MV_Product::inRandomOrder()->where('isactive', 'Y')->where('iswebstorefeatured', 'Y')->get();

    }
}