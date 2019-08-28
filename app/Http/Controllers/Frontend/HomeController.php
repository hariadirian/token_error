<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Services\TicketService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Route;
class HomeController extends Controller
{

    public function __construct(ProductService $product){
        if(Route::currentRouteName() == 'product.view' or Route::currentRouteName() == 'promo.view'){
            if(Route::current()->parameter('login') == 'Y'){
                $this->middleware('auth');
            }
        }
        $this->product = $product;
    }
    
    public function homePage(){

        $data['promotion']  = $this->product->promotionActive();   
        $data['products']   = $this->product->regularActive();     
        return view('frontend.home.home-page', $data);

    }

    public function product($popular = null){

        $data['products']   = $this->product->regularActive(null, $popular);   
        return view('frontend.home.product', $data);
        
    }

    public function viewProduct($cd, $login = null) {

        $data['det_product']  = $this->product->regularActive($cd);
        $data['randomProduk'] = $this->product->regularRandomActive($cd);
        return view('frontend.home.view-product', $data);

    }

    public function promotion($popular = null){

        $data['promotion'] = $this->product->promotionActive(null, $popular);   
        return view('frontend.home.promotion', $data);
        
    }

    public function viewPromotion($cd = null) {
        
        $data['det_promo'] = $this->product->promotionActive($cd);   
        return view('frontend.home.view-promo', $data);

    }

    public function checkPrice(Request $request, TicketService $ticket) {
        
        $result = $ticket->getPrice($request->type, $request->date, $request->cd, $request->qty, null, $request->promo_code);

        if(isset($result['state'])){
            if($result['state'] == 0){
                return json_encode(array('failed', $result['message']));
            }
        }
        $content = preg_replace( "/\r|\n/", "", view('frontend.home.view-price', $result) );
        return json_encode(array($content));
    }
}
