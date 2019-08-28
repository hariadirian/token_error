<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Snowfire\Beautymail\Beautymail;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Idempiere\M_View\MV_Product;
use DB;
use Session;
use Auth;
use App\Models\M_Cart_Product_HD;
use App\Models\M_Cart_Product_BD;
use App\Models\M_Cart_Product_DT;
use App\Models\M_Promo_TX_HD;
use App\Models\M_Promo_TX_DT;
use App\Services\TicketService;
use App\Services\ProductService;
use stdClass;
use PDF;

class CartController extends Controller
{
    public function __construct(ProductService $product){
        $this->middleware('auth');
        $this->product = $product;
    }

    public function index(TicketService $ticket, $cd = null){

        if(!Auth::user()){
            return redirect('/login');
        }

        $carts = M_Cart_Product_HD::where('state', 'Y')
            ->where('is_active', 'Y')
            ->where('is_done', 'N')
            ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
            ->with(['toCartProductBd' => function($query){
                $query->where('is_active', 'Y');
                $query->where('state', 'Y');
                $query->with(['toTicketImgHd' => function($query1) {
                    $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
                    $query1->with(['toTicketImgDt' => function($query2){
                        $query2->where('state', 'Y');
                        $query2->where('img_type', 'BOX');
                    }]);
                }]);
            }])->get();

        $det_promo = $this->product->promotionActive($cd);
            // dd($data['carts']);

        return view('frontend.cart.view-cart', compact('carts', 'det_promo'));
    }

    public function choosepayment(){

    }

    public function addToCart(TicketService $ticket){

        $id_produk           = Input::post('cd');
        $quantity            = Input::post('quantity');
        $type                = Input::post('type_ticket');
        $ticket_date         = Input::post('ticket_date');
        $by_pass             = Input::post('by_pass');
        $promo_code          = Input::post('promo_code');
        $type_customer       = Input::post('type_customer');

        $price = $ticket->getPrice($type, $ticket_date, $id_produk, $quantity, $by_pass, $promo_code);

        if ($by_pass) {
            $quantity = $price['result']->min_val;
            $price['result']->amount_fix = 0;
        }

        if(isset($price['state']) and !$by_pass){
            if($price['state'] == 0){
                return json_encode([
                    'state' => false,
                    'message' => $price['message']
                ]);
            }
        }

        if($id_produk){

            if(Auth::guard('administrator')->user()){
                return redirect()->back()->with('failed', 'You are currently logged in backend app. Please log out first before checking out the order.');
            }

            $check_product = M_Cart_Product_HD::where('state', 'Y')
                            ->where('is_active', 'Y')
                            ->where('is_done', 'N')
                            ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
                            ->with(['toCartProductBd' => function($query1) use($id_produk) {
                                $query1->where('cd_product_ref', $id_produk);
                                $query1->where('is_active', 'Y');
                                $query1->where('state', 'Y');
                            }])->first();

            if(!$check_product){
                //INSERT NEW CART HD
                $cartHd = $this->createCartHd();
                $cartHdCd = $cartHd->id_et_cart_product_hd;

            }else{

                if($check_product->toCartProductBd->count()){

                    if ($by_pass) {
                        return redirect()->route('cart');
                    }

                    M_Cart_Product_BD::where('id_et_cart_product_bd', $check_product->toCartProductBd->first()->id_et_cart_product_bd)
                    ->update(['is_active' => 'N']);
                }

                $cartHdCd = $check_product->id_et_cart_product_hd;
            }

            $price = $price['result'];

            $cartBd = $this->createCartBd(
                $cartHdCd,
                $id_produk,
                $quantity,
                $type,
                $ticket_date,
                $price->amount_fix,
                $price->name
            );

            if ($by_pass) {
                return redirect()->route('cart');
            }

            if($type == 'PROMOTION'){
                $promoHd = $this->createPromoHd($cartBd->id_et_cart_product_bd);
                $promoDt = $this->createPromoDt($price, $id_produk, $promoHd->id_et_promo_tx_hd);
            }

            $cartDt = $this->createCartDt(
                $cartBd->id_et_cart_product_bd, $price, $type
            );

            return redirect()->route('cart');

        }else{
            return redirect('/');
        }
    }


    public function cartUpdate(TicketService $ticket){

        $date           = Input::post('date');
        $qty            = Input::post('qty');
        $type           = Input::post('type');
        $product        = Input::post('product');
        $hdCd           = Input::post('hdCd');

        $price          = $ticket->getPrice($type, $date, $product, $qty);

        if(isset($price['state'])){
            if($price['state'] == 0){
                $msg            = $price['message'];
                if(isset($price['val'])){
                    $qty            = $price['val'];
                    $price          = $ticket->getPrice($type, $date, $product, $qty);
                }else{
                    return json_encode([
                        'state' => false,
                        'message' => $price['message']
                        ]);
                }
            }
        }
        $price = $price['result'];

        M_Cart_Product_BD::where('id_et_cart_product_hd', $hdCd)->where('cd_product_ref', $product)->update([
            'is_active' => 'N',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id_us_frontend_hd
        ]);

        $cartBd = $this->createCartBd(
            $hdCd,
            $product,
            $qty,
            $type,
            $date,
            $price->amount_fix,
            $price->name
        );

        if($type == 'PROMOTION'){
            $promoHd = $this->createPromoHd($cartBd->id_et_cart_product_bd);
            $promoDt = $this->createPromoDt($price, $product, $promoHd->id_et_promo_tx_hd);
        }

        $cartDt = $this->createCartDt(
            $cartBd->id_et_cart_product_bd, $price, $type
        );

        $sum_product = M_Cart_Product_BD::where('id_et_cart_product_hd', $hdCd)->where('is_active', 'Y')->where('state', 'Y')->selectRaw('sum(total_amount) AS total_amount, sum(qty_product) AS qty_product')->get()->first();

        return json_encode([
            'state' => true,
            'message' => isset($msg)? $msg : '',
            'qty' => $qty,
            'total_qty' => $sum_product['qty_product'],
            'date' => $date,
            'date_text' =>  date("l, d F Y", strtotime($date)),
            'product_price' => 'Rp. '.number_format($price->amount_raw, 0, ',', '.'),
            'amount' => 'Rp. '.number_format($price->amount_fix, 0, ',', '.'),
            'total_amount' => 'Rp. '.number_format($sum_product['total_amount'], 0, ',', '.')
        ]);

    }

    public function createCartHd(){

        $hd_datas   =   [
                            "cd_et_cart_product_hd" => get_prefix('et_cart_product_hd'),
                            "id_us_frontend_hd"     => Auth::user()->id_us_frontend_hd,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];

        return M_Cart_Product_HD::create($hd_datas);

    }

    public function createCartBd($id_et_cart_product_hd, $id_produk, $quantity, $type, $ticket_date, $amount, $name){

        $bd_datas   =   [
                            "cd_et_cart_product_bd" => get_prefix('et_cart_product_bd'),
                            "id_et_cart_product_hd" => $id_et_cart_product_hd,
                            "cd_product_ref"        => $id_produk,
                            "product_name"          => $name,
                            "ticket_type"           => $type,
                            "ticket_date"           => $ticket_date,
                            "qty_product"           => $quantity,
                            "total_amount"          => $amount,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];

        return M_Cart_Product_BD::create($bd_datas);

    }

    public function createCartDt($id_et_cart_product_bd, $price, $type){

        if($type == 'PROMOTION'){

            $product_uu = json_decode($price->product_uu);
            $m_attributeset_uu = json_decode($price->m_attributeset_uu);
            $product_name = json_decode($price->product);
            $product_val = json_decode($price->product_value);

            foreach (json_decode($price->product_uu) as $key => $product) {

                $dt_datas[]   =   [
                                "cd_et_cart_product_dt" => get_prefix('et_cart_product_dt'),
                                "id_et_cart_product_bd" => $id_et_cart_product_bd,
                                "m_product_uu"          => $product,
                                "m_attributeset_uu"     => $m_attributeset_uu[$key],
                                "ticket_name"           => $product_name[$key],
                                "amount"                => $product_val[$key],
                                "qty_ticket"            => $price->qty,
                                "created_by"            => Auth::user()->id_us_frontend_hd,
                            ];
            }
        }elseif($type == 'PRODUCT'){

            $dt_datas[]   =   [
                            "cd_et_cart_product_dt" => get_prefix('et_cart_product_dt'),
                            "id_et_cart_product_bd" => $id_et_cart_product_bd,
                            "m_product_uu"          => $price->product_uu,
                            "m_attributeset_uu"          => $price->m_attributeset_uu,
                            "ticket_name"           => $price->name,
                            "amount"                => $price->amount_raw,
                            "qty_ticket"            => $price->qty,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];
        }

        M_Cart_Product_DT::insert($dt_datas);
        return true;
    }

    public function createPromoHd($cart_bd_cd){

        $check_promo = M_Promo_TX_HD::where('state', 'Y')
            ->where('is_active', 'Y')
            ->where('id_et_cart_product_bd', $cart_bd_cd)
            ->first();

        if($check_promo){
            $result                     = new stdClass();
            $result->id_et_promo_tx_hd  = $check_promo->id_et_promo_tx_hd;
            return $result;
        }

        $promo_hd_datas   =   [
                            "cd_et_promo_tx_hd"     => get_prefix('et_promo_tx_hd'),
                            "id_et_cart_product_bd" => $cart_bd_cd,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];

        return M_Promo_TX_HD::create($promo_hd_datas);

    }

    public function createPromoDt($price, $id_produk, $promoHd){

        $check_promo = M_Promo_TX_DT::where('state', 'Y')
            ->where('is_active', 'Y')
            ->where('id_et_promo_tx_hd', $promoHd)
            ->where('cd_promo_ref', $id_produk)
            ->first();

        if($check_promo){
            return true;
        }
        $promo_type = $price->promo_type == 'P'? 'PERCENT' : 'ABSOLUTE';
        $promo_dt_datas   =   [
                            "cd_et_promo_tx_dt"     => get_prefix('et_promo_tx_dt'),
                            "id_et_promo_tx_hd"     => $promoHd,
                            "category"              => 'SYSTEM',
                            "cd_promo_ref"          => $id_produk,
                            "type_promo"            => $promo_type,
                            "min_val_promo"         => $price->min_val,
                            "amount_promo"          => $price->promo,
                            "product_promo"         => $price->product,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];

        return M_Promo_TX_DT::create($promo_dt_datas);

    }


    public function removeCartBd(){

        $product        = Input::post('product');
        $hdCd           = Input::post('hdCd');

        $delete = M_Cart_Product_BD::where('id_et_cart_product_hd', $hdCd)
                    ->where('cd_product_ref', $product)
                    ->where('is_active', 'Y')
                    ->update(
                        array(
                            'deleted_at' => date('Y-m-d h:i:s'),
                            'deleted_by' => Auth::user()->id_us_frontend_hd,
                            'is_active' => 'N')
                        );

        $sum_product = M_Cart_Product_BD::where('id_et_cart_product_hd', $hdCd)->where('is_active', 'Y')->where('state', 'Y')->selectRaw('sum(total_amount) AS total_amount, sum(qty_product) AS qty_product')->get()->first();

        return json_encode(['state' => $delete, 'qty_product' => $sum_product['qty_product'], 'total_amount' => 'Rp. '.number_format($sum_product['total_amount'], 0, ',', '.')]);

    }
}

            // $check_product =  M_Cart_Product_HD::where('state', 'Y')
            //                     ->where('is_active', 'Y')
            //                     ->where('is_done', 'N')
            //                     ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
            //                     ->with('toCartProductBd')
            //                     ->whereHas('toCartProductBd', function ($query1) use($id_produk) {
            //                         $query1->where('cd_product_ref', $id_produk);
            //                         $query1->where('is_active', 'Y');
            //                         $query1->where('state', 'Y');
            //                     })->get();
