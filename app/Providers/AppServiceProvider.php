<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use DB;
use Auth;
use Illuminate\Http\Request;
use App\Services\ProductService;

use App\Models\M_Cart_Product_HD;
use App\Models\M_Custom_Query;
class AppServiceProvider extends ServiceProvider
{

    public function boot(Request $request, ProductService $product)
    {
        Schema::defaultStringLength(191);
        
        \Illuminate\Support\Facades\Validator::extend('is_active', function($attribute, $value,$parameters,$validator){

            $model = \App\Models\M_User_Management\M_Us_Frontend_HD::where($attribute, $value)->where('is_active', 'Y')->where('state', 'Y')->first();
            return $model ? true : false;
            
        }, 'User belum aktif, mohon cek kembali email anda.');

        view()->composer('*', function($view) use($product) {

            if(!auth()->user()){

                View::share('cart_count', 0);
                View::share('cart_sum', 0);
           
            }else{
                
                $cart_hd = M_Cart_Product_HD::where('state', 'Y')
                    ->where('is_active', 'Y')
                    ->where('is_done', 'N')
                    ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
                    ->with(['toCartProductBd' => function($query1){
                        $query1->where('is_active', 'Y');
                        $query1->where('state', 'Y');
                    }])->first();

                $total      = 0;
                $total_qty  = 0;
                if(isset($cart_hd->toCartProductBd)){
                    foreach($cart_hd->toCartProductBd as $no => $cartBd){
                        $total      += $cartBd->total_amount;
                        $total_qty  += $cartBd->qty_product;
                    }
                }
                View::share('cart_count', $total_qty);
                View::share('cart_sum', $total);
            }

            $regulars    = $product->regularActive();
            foreach($regulars as $key => $reg){
                $menu_reg[strtoupper($reg->product_category)][$key]['name']  = $reg->name;
                $menu_reg[strtoupper($reg->product_category)][$key]['cd']    = $reg->m_product_uu;
            }
            View::share('menu_reg', $menu_reg);
            
            $promotions   = $product->promotionActive();
            foreach($promotions as $key => $promo){
                $menu_promo[strtoupper($promo->type_customer)][$key]['name']  = $promo->description;
                $menu_promo[strtoupper($promo->type_customer)][$key]['cd']    = $promo->m_promotion_uu;
            }
            View::share('menu_promo', $menu_promo);

            $view->with('user', auth()->user());
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Helpers/MyHelper.php');
    }
}
