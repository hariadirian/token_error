<?php

namespace App\Http\Controllers\Frontend;
//OTHER STUFF
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Mail\TmiiWebEmail;
use Snowfire\Beautymail\Beautymail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Session;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Auth;
use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

// use Request;

//MODELS
use App\Models\M_Cart_Product_HD;
use App\Models\M_Cart_Product_DT;
use App\Models\M_Ordered_Ticket_HD;
use App\Models\M_Ordered_Ticket_DT;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Idempiere\M_View\MV_Product;
use App\Models\M_User_Management\M_Us_Frontend_HD;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use App\Models\M_Custom_Query;
use App\Models\M_Event_Calendar;
use App\Models\M_Cart_Product_BD;
use App\Models\M_Ordered_Ticket_Txes;

/**
 * @method sendEmail()
 */
class EticketingController extends Controller
{
    public static function mystatic($f = 0){
        return $f+23 ;
    }

    public function view_cart(){

        if(!Auth::user()){
            return redirect('/login');
        }

        $data['identity'] = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)->first();

        $data['carts'] = M_Cart_Product_HD::where('state', 'Y')
        ->where('is_active', 'Y')
        ->where('is_done', 'N')
        ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
        ->with(['toCartProductDt' => function($query){
            $query->where('is_active', 'Y');
            $query->where('state', 'Y');
        }])->first();

        $data['product'] = [];

        if(isset($data['carts']->toCartProductDt)){
            foreach ($data['carts']->toCartProductDt as $no => $cart) {
                if ($cart->ticket_type == 'PROMOTION') {
                    $products =  MV_Promo::where('m_promotion_uu', $cart->idem_ticket_uu)->first();

                    $pro = str_replace('{', '', $products->promoline);
                    $pro = str_replace('}', '', $pro);
                    $arrProm1 = explode(',', $pro);
                    foreach ($arrProm1 as $dat1) {
                        $arrProm2 = explode(';', $dat1);
                        foreach ($arrProm2 as $dat2) {
                            if ($dat2 == 'I') {
                                $minOP = $arrProm2[1];
                                $minVal = $arrProm2[2];
                            }
                    
                            if ($dat2 == 'X') {
                                $maxOP = $arrProm2[1];
                                $maxVal = $arrProm2[2];
                            }
                        }
                    }

                    if (!isset($minVal)) {
                        $minVal = 1;
                        $minOP = '=';
                    }
                    if (!isset($maxVal)) {
                        $maxVal = 1;
                        $maxOP = '=';
                    }
                    $check_qty = qty_validate($products, $cart->qty);
                    if (isset($check_qty['state'])) {
                        if ($check_qty['state'] == 0) {
                            return $check_qty;
                        } else {
                            $qty_fix = $check_qty['result'];
                        }
                    } else {
                        return $check_qty;
                    }

                    $data['product'][$cart->idem_ticket_uu]['name']              = $products->name;
                    $data['product'][$cart->idem_ticket_uu]['price']             = $cart->existing_idem_price / $qty_fix;
                    $data['product'][$cart->idem_ticket_uu]['min_qty']           = $minVal;
                    $data['product'][$cart->idem_ticket_uu]['max_qty']           = $maxVal;
                    $data['product'][$cart->idem_ticket_uu]['qty_fix']           = $qty_fix;
                    $data['product'][$cart->idem_ticket_uu]['valid_until_date']  = $products->enddate;
                    $data['product'][$cart->idem_ticket_uu]['ticket_date']       = $cart->ticket_date;
                }else{
                    
                    $products =  MV_Product::where('m_product_uu', $cart->idem_ticket_uu)->first();

                    $data['product'][$cart->idem_ticket_uu]['name']              = $products->name;
                    $data['product'][$cart->idem_ticket_uu]['price']             = $cart->existing_idem_price / $cart->qty;
                    $data['product'][$cart->idem_ticket_uu]['min_qty']           = 0;
                    $data['product'][$cart->idem_ticket_uu]['max_qty']           = 0;
                    $data['product'][$cart->idem_ticket_uu]['qty_fix']           = $cart->qty;
                    $data['product'][$cart->idem_ticket_uu]['valid_until_date']  = '-';
                    $data['product'][$cart->idem_ticket_uu]['ticket_date']       = $cart->ticket_date;
                }
            }
        }

        if($data['identity']->first_name and $data['identity']->mobile_phone and $data['identity']->id_card){
            $data['payment'] = true;
        }else{
            $data['payment'] = false;
        }
        return view('frontend.view-cart', $data);

    }
    public function history_order(){

        if(!Auth::user()){
            return redirect('/login');
        }

        $data['history'] = M_Cart_Product_HD::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)->get();

        if($data['identity']->first_name and $data['identity']->mobile_phone and $data['identity']->id_card){
            $data['payment'] = true;
        }else{
            $data['payment'] = false;
        }
        return view('frontend.view-cart', $data);

    }

    public function collect_order()
    {
        $id_produk  = Input::post('cd');
        $quantity   = Input::post('quantity');
        $type       = Input::post('type');
        $ticket_date       = Input::post('ticket_date');
        
        if($id_produk){

            if(Auth::guard('administrator')->user()){
                return redirect()->back()->with('failed', 'You are currently logged in backend app. Please log out first before checking out the order.');
            }

            if(!Auth::user()){
                return redirect('/login/' . $id_produk);
            }

            $check_product = M_Cart_Product_HD::where('state', 'Y')
                            ->where('is_active', 'Y')
                            ->where('is_done', 'N')
                            ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
                            ->with(['toCartProductDt' => function($query) use($id_produk) {
                                $query->where('idem_ticket_uu', $id_produk);
                                $query->where('is_active', 'Y');
                                $query->where('state', 'Y');
                            }])->first();
            
                            if($type == 'PRODUCT'){
                                $result = $this->getPriceProduct($ticket_date, $id_produk, $quantity);
                            }else{
                                $result = $this->getPrice($ticket_date, $id_produk, $quantity);
                            }
            if(!$check_product){
                //INSERT NEW CART HD
                $hd_new = $this->create_new_cart_hd();


                $dt_new = $this->create_new_cart_dt($hd_new->id_et_cart_product_hd, $id_produk, $quantity, $type, $ticket_date, $result['total_amountraw']);

            }else{

                if(!$check_product->toCartProductDt->count()){
                    //INSERT NEW CART DT
                    $dt_new = $this->create_new_cart_dt($check_product->id_et_cart_product_hd, $id_produk, $quantity, $type, $ticket_date, $result['total_amountraw']);
                }
            }

            return redirect()->route('cart');

        }else{
            return redirect('/');
        }
    }

    // public function assign_ticket_date($date){
    
    //     if(!Auth::user() or !$date){
    //         return "false";
    //     }    
    //     $ticket_date = M_Cart_Product_HD::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
    //                     ->where('state', 'Y')
    //                     ->where('is_active', 'Y')
    //                     ->where('is_done', 'N');

    //     $date = DateTime::createFromFormat('d-m-Y', $date);
    //     $date = $date->format('Y-m-d');

    //     if($ticket_date->update(array(
    //         'ticket_date' => $date,
    //         'updated_at' => date('Y-m-d h:i:s'), 
    //         'updated_by' => Auth::user()->id_us_frontend_hd))
    //     ){
    //         return "true";
    //     }else{
    //         return "false";
    //     }

    // }

    public function check_price_by_ticket_date($date, $cd, $qty){
    
        $result = $this->getPrice($date, $cd, $qty);

        if(isset($result['state'])){
            if($result['state'] == 0){
                return json_encode(array('failed', $result['message']));
            }
        }
        $content = preg_replace( "/\r|\n/", "", view('frontend.view-price', $result) );
        return json_encode(array($content));

    }

    public function check_price_product_by_ticket_date($date, $cd, $qty){
    
        $result = $this->getPriceProduct($date, $cd, $qty);

        if(isset($result['state'])){
            if($result['state'] == 0){
                return json_encode(array('failed', $result['message']));
            }
        }
        $content = preg_replace( "/\r|\n/", "", view('frontend.view-price-product', $result) );
        return json_encode(array($content));

    }

    public function remove_cart($cd){
    
        if(!Auth::user()){
            return "false";
        }    
        $ticket = M_Cart_Product_DT::where('cd_et_cart_product_dt', $cd);
        $update = $ticket->update(
                    array(
                        'deleted_at' => date('Y-m-d h:i:s'), 
                        'deleted_by' => Auth::user()->id_us_frontend_hd,
                        'is_active' => 'N')
                    );

        if($update){
            die("true");
        }else{
            die("false");
        }

    }

    public function assign_user_profile(Request $request){
    
        if(!Auth::user()){
            return redirect('/login');
        }

        $this->validate($request, [
            'nama_depan' => 'required|string|max:64',
            'nama_belakang' => 'string|max:64',
            'no_hp' => 'required|string|max:64|unique:us_frontend_dt,mobile_phone',
            'alamat' => 'string|max:128',
            'institusi' => 'string|max:64',
            'ktp' => 'required|string|max:64|unique:us_frontend_dt,id_card',
        ]);

        $dt_datas   =   [
            "first_name"            => $request->post('nama_depan'),
            "last_name"             => $request->post('nama_belakang'),
            "mobile_phone"          => $request->post('no_hp'),
            "address"               => $request->post('alamat'),
            "institute"             => $request->post('institusi'),
            "id_card"               => $request->post('ktp'),
            "updated_by"            => Auth::user()->id_us_frontend_hd,
            "updated_at"            => date('Y-m-d H:i:s'),
        ];

        $update = M_Us_Frontend_DT::where('cd_us_frontend_dt', $request->post('cd'))->update($dt_datas);
        
        if($update){
            return redirect('/cart');
        }
        die('Terjadi kesalahan!');
        
    }
    
    public function assign_payment(Request $request){
    
        if(!Auth::user()){
            return redirect('/login');
        }

        $this->validate($request, [
            'metode_pembayaran' => 'required|string|max:64',
        ]);

        if($request->post('metode_pembayaran') == 'DEBIT'){

            $this->validate($request, [
                'nama_bank'     => 'required|string|max:64',
                'nama_rek'      => 'required|string|max:128',
            ]);

            $hd_datas   =   [
                "payment_method"        => $request->post('metode_pembayaran'),
                "bank_name"             => $request->post('nama_bank'),
                "account_name"          => $request->post('nama_rek'),
                "is_done"               => 'Y',
                "done_by"            => Auth::user()->id_us_frontend_hd,
                "done_at"            => date('Y-m-d H:i:s'),
            ];echo('horeeee');die;

        }elseif($request->post('metode_pembayaran') == 'CREDIT'){

            $this->validate($request, [
                'nomor_kartu'   => 'required|string|max:32',
                'tgl_expired'   => 'required|string|max:8',
                'cvv'           => 'required|string|max:8',
            ]);

            $hd_datas   =   [
                "payment_method"        => $request->post('metode_pembayaran'),
                "cc_number"             => $request->post('nomor_kartu'),
                "cc_expired_date"       => $request->post('tgl_expired'),
                "cc_cvv"                => $request->post('cvv'),
                "is_done"               => 'Y',
                "done_by"            => Auth::user()->id_us_frontend_hd,
                "done_at"            => date('Y-m-d H:i:s'),
            ];
        }else{

            $hd_datas   =   [
                "payment_method"        => $request->post('metode_pembayaran'),
                "is_done"               => 'Y',
                "done_by"            => Auth::user()->id_us_frontend_hd,
                "done_at"            => date('Y-m-d H:i:s'),
            ];
        }

        $update = M_Cart_Product_HD::where('cd_et_cart_product_hd', $request->post('cd_p'))->update($hd_datas);

        if($update){

            $cart = M_Cart_Product_HD::where('cd_et_cart_product_hd', $request->post('cd_p'))
                    ->with(['toCartProductDt' => function($query){
                        $query->where('is_active', 'Y');
                        $query->where('state', 'Y');
                    }])->first();

            $check_order = M_Ordered_Ticket_HD::where('id_et_cart_product_hd', $cart->id_et_cart_product_hd);

            if($check_order->count()){

                $check_order->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::user()->id_us_frontend_hd, 'is_active' => 'N' ]);
            }

            $hd_datas   =   [
                "cd_et_ordered_ticket_hd"   => get_prefix('et_ordered_ticket_hd'),
                "id_et_cart_product_hd"     => $cart->id_et_cart_product_hd,
                "id_us_frontend_hd"         => $cart->id_us_frontend_hd,
                "ticket_date"               => $cart->ticket_date,
                "payment_method"            => $cart->payment_method,
                "bank_name"                 => $cart->bank_name,
                "account_name"              => $cart->account_name,
                "cc_number"                 => $cart->cc_number,
                "cc_expired_date"           => $cart->cc_expired_date,
                "cc_cvv"                    => $cart->cc_cvv,
                "created_by"                => Auth::user()->id_us_frontend_hd,
            ];

            if($order_hd = M_Ordered_Ticket_HD::create($hd_datas)){


                $check_order = M_Ordered_Ticket_DT::where('id_et_ordered_ticket_hd', $order_hd->id_et_ordered_ticket_hd);

                if($check_order->count()){

                    $check_order->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::user()->id_us_frontend_hd, 'is_active' => 'N' ]);
                }

                $total_price = 0;
                $total_qty = 0;
                foreach($cart->toCartProductDt as $dtcart){
                    $mvpromo      = MV_Promo::where('m_promotion_uu', $dtcart->idem_ticket_uu)->first();

                    $pro = str_replace('{','',$mvpromo->promoline);
                    $pro = str_replace('}','',$pro);
                    $arrProm1 = explode(',', $pro);
                    foreach($arrProm1 as $dat1){
                        $arrProm2 = explode(';', $dat1);
                        foreach($arrProm2 as $dat2){
                        
                            if($dat2 == 'I'){
                            $minOP = $arrProm2[1];
                            $minVal = $arrProm2[2];
                            }
                    
                            if($dat2 == 'X'){
                            $maxOP = $arrProm2[1];
                            $maxVal = $arrProm2[2];
                            } 
                        
                        }
                    }

                    if(!isset($minVal)){
                        $minVal = 1;
                        $minOP = '=';
                    }
                    if(!isset($maxVal)){
                        $maxVal = 1;
                        $maxOP = '=';
                    }
                    
                    if($mvpromo->rewardtype == 'P'){
                        $price = ($mvpromo->totalproductprice * $minVal) - ( ($mvpromo->totalproductprice * $minVal) *($mvpromo->amount/100));
                    }elseif($mvpromo->rewardtype == 'A'){
                        $price =  ($mvpromo->totalproductprice * $minVal) - $mvpromo->amount;
                    }

                    $dt_datas   =   [
                        "cd_et_ordered_ticket_dt"   => get_prefix('et_ordered_ticket_dt'),
                        "id_et_ordered_ticket_hd"   => $order_hd->id_et_ordered_ticket_hd,
                        "idem_ticket_uu"            => $dtcart->idem_ticket_uu,
                        "ticket_type"               => $dtcart->ticket_type,
                        "qty"                       => $dtcart->qty,
                        "existing_idem_price"       => $price,
                        "created_by"                => Auth::user()->id_us_frontend_hd,
                    ];

                    $total_price += $price * $dtcart->qty;

                    $total_qty += $dtcart->qty;

                    M_Ordered_Ticket_DT::create($dt_datas);
                }

                if($this->email_reservation_ticket($order_hd, $total_price, $total_qty)){
                    return redirect('/cart');
                }else{
                    die('Data sudah masuk, namun mengalami kendala pada email. Silahkan hubungi cs tmii.eticketing.');
                }
            }
            
            die('Terjadi kesalahan Passing Data Ke Order!');
        }
        die('Terjadi kesalahan Update Metode Pembayaran!');
        
    }

    public function create_new_cart_hd(){
    
        $hd_datas   =   [
                            "cd_et_cart_product_hd" => get_prefix('et_cart_product_hd'),
                            "id_us_frontend_hd"     => Auth::user()->id_us_frontend_hd,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ];

        return M_Cart_Product_HD::create($hd_datas);

    }

    public function total(){
        $total_qty = 0;
        $total = 0;
        if(Input::post('val')){
            foreach(Input::post('val') as $dat){
                $cd = explode('-', $dat['name'])[1].'-'.explode('-', $dat['name'])[2];
                $value = $dat['value'];
                
                $cart_dt = M_Cart_Product_DT::where('cd_et_cart_product_dt', $cd)->where('is_active', 'Y')->where('state', 'Y')->first();
                
                $id_produk = $cart_dt->idem_ticket_uu;
                $result = $this->getPrice($cart_dt->ticket_date, $id_produk, $value);
                if(isset($result['state'])){
                    if($result['state'] == 0){
                        return json_encode(array('failed', $result['message']));
                    }
                }
                if(!$cart_dt->update(array(
                    'qty' => $value,
                    'existing_idem_price' => $result['total_amountraw'],
                    'updated_at' => date('Y-m-d h:i:s'), 
                    'updated_by' => Auth::user()->id_us_frontend_hd))){
                        return 0;
                }
                $total_qty += $value;
                $total += $result['total_amountraw'];
            }
        }
        
        return json_encode(array(number_format($total,0,',','.'), $total_qty));

    }

    public function create_new_cart_dt($id_et_cart_product_hd, $id_produk, $quantity, $type, $ticket_date, $amount){

        $dt_datas   =   [
                            "cd_et_cart_product_dt" => get_prefix('et_cart_product_dt'),
                            "id_et_cart_product_hd" => $id_et_cart_product_hd,
                            "idem_ticket_uu"        => $id_produk,
                            "ticket_type"           => $type,
                            "ticket_date"           => $ticket_date,
                            "existing_idem_price"   => $amount,
                            "qty"                   => $quantity,
                            "created_by"            => Auth::user()->id_us_frontend_hd,
                        ]; 

        return M_Cart_Product_DT::create($dt_datas);

    }


    public function email_reservation_ticket($order_hd, $total_price, $total_qty){
        
        $user = $order_hd->toUsFrontendHd()->first();
        
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);

        if($order_hd->payment_method == 'DEBIT'){
            $conf_email = 'mail.email-reservation-debit';
        }elseif($order_hd->payment_method == 'CREDIT'){
            $conf_email = 'mail.email-reservation-credit';
        }elseif($order_hd->payment_method == 'MANDIRI_SUPPLY_CHAIN'){
            $conf_email = 'mail.email-reservation-mandiri';
        }else{
            die('Terjadi kesalahan pada pengiriman email confirmasi pemesanan!');
        }

        $beautymail->send($conf_email, [
            'no_order'=> explode('-', $order_hd->cd_et_ordered_ticket_hd)[1], 
            'nama'=> $user->toUsFrontendDt()->first()->first_name.' '.$user->toUsFrontendDt()->first()->last_name, 
            'nik'=> $user->toUsFrontendDt()->first()->id_card, 
            'email'=> $user->email, 
            'no_hp'=> $user->toUsFrontendDt()->first()->mobile_phone, 
            'tanggal_tiket'=> $order_hd->ticket_date, 
            'kuota'=> $total_qty, 
            'nama_bank'=> $order_hd->bank_name, 
            'nama_pentransfer'=> $order_hd->account_name, 
            'product'=> "TIKET", 
            'results' => $order_hd,
            'total_price' => $total_price,
            'category_bpartner'=> 'CATEGORY_BPARTNER', 
            'bpartner' => 'BPARTNER'
            ],   
            function($message) use($user) {
                $email = $user->email;
                $message
                ->from('customers@tmii.reservation.com')
                ->to($email, 'TMII')
                ->subject('[RESERVASI TIKET TMII] - Laporan pemesanan tiket TMII');
            });

            return true;
    }

    public function create_ticket($cd_ordered_hd, $is_paid){
        $product_name = MV_Promo::get();

        $name = 'Krunal';
        Mail::to('muhammad.agya7@gmail.com')->send(new TmiiWebEmail($name));
        
        return 'Email was sent';
    }


    
    //----------------------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------------------

    public function confirm_reservation()
    {

        if(Input::post('type-rombongan') and Input::post('type-pengunjung') == 'Rombongan'){
            $bpartner_id = Input::post('type-rombongan');
        }else{
            //Individu Umum
            $bpartner_id = 1000524;
        }
        $results = M_Custom_Query::checkPrice(Input::post('products'), Input::post('Kuota'), $bpartner_id);
        $product_name = M_Custom_Query::getProductsAndCategories(Input::post('products'))[0];

        $rombongan = M_Custom_Query::getBPandBPCategory($bpartner_id)[0];

        $data = [
            'type' => Input::post('type-pengunjung'),
            'categories' => $product_name->product_category_name,
            'products' => $product_name->product_name,
            'rombongan' => $rombongan->bpartner_name,
            'rombongan_id' => $bpartner_id,
            'products_id' => Input::post('products'),
            'quantity' => Input::post('Kuota'),
            'price' => $results['pricelist'],
            'discount' => $results['discount'].'%',
            'subtotal' => $results['subtotal']
        ];

        return view('pra-login-views.confirm-reservation', $data);
    }

    public function submit_reservation()
    {
        
        $results = M_Custom_Query::checkPrice(Input::post('product'), Input::post('kuota'), Input::post('rombongan'));
        $uniqcode = date('ymdHis').mt_rand(1000, 9999);

        if(Session::has('bpartnergroup')){
            $bpartner_id = Session::get('c_bpartner_id');
            if(Session::get('c_bp_group_id') == 1000108){
                $category_bpartner = 'MARKETING';
            }elseif(Session::get('c_bp_group_id') == 1000105){
                $category_bpartner = 'AGEN';
            }else{
                $category_bpartner = '';
            }
        }else{

            if(Input::post('rombongan') != 1000524){
                //marketing
                $bpartner_id = Input::post('rombongan');
                $category_bpartner = 'MARKETING-PERORANGAN';
                $instansi = Input::post('instansi');
            }else{
                //perorangan
                $bpartner_id = 1000524;
                $category_bpartner = 'PERORANGAN';
                $instansi = 'PERORANGAN';
            }
        }
        
        $get_newnum = M_Custom_Query::insertOrderedCustomer(
            $uniqcode,
            Input::post('alamat'), 
            Input::post('email'),
            Input::post('ktp'),
            Input::post('kuota'),
            Input::post('nama_bank'),
            Input::post('nama_lengkap'),
            Input::post('nama_pentransfer'),
            Input::post('no_hp'),
            Input::post('product'),
            Input::post('tanggal_tiket'),
            $instansi,
            $bpartner_id,
            $category_bpartner
        );

        $product_name = M_Custom_Query::getProductsAndCategories(Input::post('product'))[0];
        $bpartner_name = M_Custom_Query::getBPandBPCategory($bpartner_id)[0]->bpartner_name;

        if($category_bpartner == 'PERORANGAN'){
            
            $assigned_order_id = M_Custom_Query::checkAssignOrderByNoOrder($uniqcode.$get_newnum)[0]->assigned_order_id;

            $promo = M_Custom_Query::checkPrice(Input::post('product'), Input::post('kuota'), $bpartner_id);
                
            M_Custom_Query::assignSavePayment($promo, Input::post('kuota'), $assigned_order_id);
        
        }
        
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('emails.daftarpasienbaru', [
            'no_order'=> $uniqcode.$get_newnum, 
            'nama'=> Input::post('nama_lengkap'), 
            'nik'=> Input::post('ktp'), 
            'email'=>Input::post('email'), 
            'no_hp'=>Input::post('no_hp'), 
            'tanggal_tiket'=>Input::post('email'), 
            'kuota'=>Input::post('kuota'), 
            'nama_bank'=>Input::post('nama_bank'), 
            'nama_pentransfer'=>Input::post('nama_pentransfer'), 
            'tanggal_tiket'=>Input::post('tanggal_tiket'), 
            'product'=> $product_name->product_category_name . ' - '.$product_name->product_name, 
            'results'=> $results, 
            'category_bpartner'=> $category_bpartner, 
            'bpartner' => $bpartner_name
            ],   
            function($message) {
                $email = Input::post('email');
                $message
                ->from('customers@klinikmatautamatangsel.com')
                ->to($email, 'TMII')
                ->subject('[RESERVASI TIKET TMII] - Laporan pemesanan tiket TMII');
            });

            session([
                'no_order' => $uniqcode.$get_newnum,
            ]);

           return  redirect('cek_order');
        // return view('pra-login-views.reservation');
    }



    public function cek_order()
    {

        return view('pra-login-views.cek_order');
    }

    public function search_order(Request $request)
    {

        if(Session::has('no_order')){
            $no_order = Session::get('no_order');
            $request->session()->flush();
        }else{
            $no_order = Input::post('no_order');
        }

        $getData = M_Custom_Query::getAssignedCustMarkPer($no_order);

        if(isset($getData[0] ) ){
            $getData = $getData[0];
            if($getData->assigned_order_id != ''){

                $getBpartner = M_Custom_Query::getBPandBPCategory($getData->bpartner_id);
                $getProduct = M_Custom_Query::getProductById($getData->m_productprice_id);
            }else{

                $getBpartner = M_Custom_Query::getBPandBPCategory($getData->bpartner_ordered_id);
                $getProduct = M_Custom_Query::getProductsAndCategoriesForCek($getData->product_ordered_id);
            }

            $data = [
                'getData' => $getData,
                'getBpartner' => $getBpartner,
                'getProduct' => $getProduct,
                'no_order' => $no_order
            ];
        }else{
            $data = [
                'notfound' => true
            ];
        }
        return view('pra-login-views.cek_order', $data);
    }


    public function manual_payment($no_order)
    {
        $datapayment = M_Custom_Query::ManualPayment($no_order)[0];


        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $email2 = $datapayment->assigned_cp_email;
        $beautymail->send('emails.penerimaanpembayaran', [
            'nama'=> $datapayment->assigned_cp_nama_lengkap, 
            'no_order'=> $datapayment->no_order, 
            'paid_at'=> $datapayment->paid_at, 
            'no_pembayaran'=> $datapayment->no_pembayaran, 
            'keterangan_pembayaran'=> $datapayment->keterangan_pembayaran, 
            // 'qrcode'=>DNS2D::getBarcodeHTML($datapayment->no_order, "QRCODE")
            ],   
            function($message) use($email2){

                $email = $email2;
                $message
                ->from('customers@klinikmatautamatangsel.com')
                ->to($email, 'TMII')
                ->subject('[RESERVASI TIKET TMII] - Laporan penerimaan pembayaran tiket TMII')->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('Content-Type', 'text/html');
            });

    }

    //------------------------- NEW --------------------------//


    public function reservation()
    {

        // if(Input::post('type-rombongan') and Input::post('type-pengunjung') == 'Rombongan'){
        //     $bpartner_id = Input::post('type-rombongan');
        // }else{
        //     //Individu Umum
        //     $bpartner_id = 1000524;
        // }

        // $results = M_Custom_Query::checkPrice(Input::post('products'), Input::post('Kuota'), $bpartner_id);
        // $product_name = M_Custom_Query::getProductsAndCategories(Input::post('products'))[0];

        // $rombongan = M_Custom_Query::getBPandBPCategory($bpartner_id)[0];

        $data = [
            'type' => Input::post('type'),
            'qty' => Input::post('qty'),
            'promo' => Input::post('promo'),
            'date' => Input::post('date'),
            // 'categories' => $product_name->product_category_name,
            // 'products' => $product_name->product_name,
            // 'rombongan' => $rombongan->bpartner_name,
            // 'rombongan_id' => $bpartner_id,
            // 'products_id' => Input::post('products'),
            // 'quantity' => Input::post('Kuota'),
            // 'price' => $results['pricelist'],
            // 'discount' => $results['discount'].'%',
            // 'subtotal' => $results['subtotal']
        ];

        return view('frontend.reservation-page', $data);
    }

    public function testing(){
        // if($order_hd->payment_method == 'DEBIT'){
            $conf_email = 'mail.email-reservation-debit';
        // }elseif($order_hd->payment_method == 'CREDIT'){
        //     $conf_email = 'mail.email-reservation-credit';
        // }elseif($order_hd->payment_method == 'MANDIRI_SUPPLY_CHAIN'){
        //     $conf_email = 'mail.email-reservation-mandiri';
        // }else{
        //     die('Terjadi kesalahan pada pengiriman email confirmasi pemesanan!');
        // }

        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send($conf_email, [
            'no_order'=> '1234567890', 
            'nama'=> 'Rian Hariadi',
            'nik'=> '6475121243', 
            'email'=> 'rianhariadi@gmail.com',
            'no_hp'=> '08213123123', 
            'tanggal_tiket'=> '2019-04-14', 
            'kuota'=> 143, 
            'nama_bank'=> 'BANK BRI', 
            'nama_pentransfer'=> 'JAJAT SUDRAJAT', 
            'product'=> "TIKET", 
            'results' => "",
            'total_price' => '10000000',
            'category_bpartner'=> 'CATEGORY_BPARTNER', 
            'bpartner' => 'BPARTNER'
            ],   
            function($message)  {
                $email = 'rianhariadi@gmail.com';
                $message
                ->from('customers@tmii.reservation.com')
                ->to($email, 'TMII')
                ->subject('[RESERVASI TIKET TMII] - Laporan pemesanan tiket TMII');
            });
            return 'selesai';
    }

    //--------------------------------smua masih disini. we'll be moved to respected files and locations---BOF
    public function genSignature( $rqDatetime,$order_id,$mode, $amount=false, $uuid=false, $ccy='IDR'){//Request $request){//

        // $rqDatetime = $request->post('rq_datetime');
        // $order_id = $request->post('order_id');
        // $mode = $request->post('mode');
        // $amount = $request->post('amount');
        // $uuid = $request->post('uuid');
        // $ccy = $request->post('ccy');

        $key = env('ESPAY_KEY');
        $comm_code = env('ESPAY_COMMCODE');
        $signature_key = env('ESPAY_SIGNATURE');

        if($mode == 'SENDINVOICE'){
            $data = "##".$signature_key."##".$uuid."##".$rqDatetime."##".$order_id."##".$amount."##".$ccy."##".$comm_code."##".$mode."##";
            // dd($data);
        }else if($mode == 'CLOSEDINVOICE'){
            $data = "##".$signature_key."##".$uuid."##".$rqDatetime."##".$order_id."##".$comm_code."##".$mode."##";
        }else{
            $data = "##".$signature_key."##".$rqDatetime."##".$order_id."##".$mode."##";
        }
        $upperCase = strtoupper($data);//dd($upperCase);
        $signature = hash('sha256', $upperCase);
        return $signature;
    }

    public function closeInv(Request $request)
    {
        $uuid = Uuid::uuid1();
        $rqDatetime = date('Y-m-d H:i:s');
        $order_id = $request->post('order_number');
        $comm_code = env('ESPAY_COMMCODE');
        $generatedSignature = $this->genSignature($rqDatetime, $order_id, 'CLOSEDINVOICE');

        $data = [
            "rq_uuid" => $uuid->toString(),
            "rq_datetime" => $rqDatetime,
            "order_id" => $order_id,
            "comm_code" => $comm_code,
            "signature" => $generatedSignature
        ];
        
        $form_field = http_build_query($data);
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://sandbox-api.espay.id/rest/merchant/closeinvoice",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $form_field,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            Storage::disk('local')->put('CloseInvVA'.date('Ymd_His').'.json', json_encode($response,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
            $retdata = json_decode($response, true);
            $retdata = [
                "status" => $retdata['error_code']
            ];
            return $retdata;
        }
    }

    public function updateINVStatus(Request $request)
    {
        $uuid = Uuid::uuid1();
        $rqDatetime = date('Y-m-d H:i:s');
        $order_id = $request->post('order_number');
        $comm_code = env('ESPAY_COMMCODE');
        $generatedSignature = $this->genSignature($rqDatetime, $order_id, 'EXPIRETRANSACTION');

        $data = [
            "uuid" => $uuid->toString(),
            "rq_datetime" => $rqDatetime,
            "order_id" => $order_id,
            "comm_code" => $comm_code,
            "tx_remark" => "Cek Transaksi atas Order ID: ".$order_id,
            "signature" => $generatedSignature
        ];
        
        $form_field = http_build_query($data);
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://sandbox-api.espay.id/rest/merchant/updateexpire",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $form_field,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            Storage::disk('local')->put('UpdateStatusInvVA'.date('Ymd_His').'.json', $response);
            $retdata = json_decode($response, true);
            if($retdata['error_code'] == "0000"){
                $this->payment_success($order_id, $espay_credit_to_bank, $espay_product, $retdata["rs_datetime"]);
            }
            $retdata = [
                "status" => $retdata['error_code']
            ];
            return $retdata;
        }
    }

    public function sendInv2(Request $request)
    {
//        $input = $request->post('input');
//        $hasil = 100*$input ;
        $retdata = [
            "hasil" => 60000,
            "Expired" => '2020',
            "Full_User_Name" => 'Rian',
        ];
        return $retdata;
    }

    public function sendInv(Request $request)
    {

        if(!Auth::user()){
            return redirect('/login');
        }
        $userdata['identity'] = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)->first();

        $comm_code = env('ESPAY_COMMCODE');
        Storage::disk('local')->put('sendInv'.date('Ymd_His').'.json', json_encode($request->post(),JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        $rqDatetime = date('Y-m-d H:i:s');
        $order_id = $request->post('order_number');
        $amount_temp = (int) $request->post('total_amount');
        $adminfee = (int) $request->post('adminfee');
        $amount = $amount_temp + $adminfee;
        $uuid = Uuid::uuid1();
        $bank_code = $request->post('bank_code');
        $payment_method = $request->post('bank_product');
        $generatedSignature = $this->genSignature($rqDatetime, $order_id, 'SENDINVOICE', $amount, $uuid, $ccy='IDR');
        $this->updateCart($order_id, $bank_code, false, $payment_method);

        $data = [
            "rq_uuid" => $uuid->toString(),
            "rq_datetime" => $rqDatetime,
            "order_id" => $order_id,
            "sender_id" => "SGOPLUS",
            "amount" => $amount,
            "ccy" => "IDR",
            "comm_code" => $comm_code,
            "remark1" => $userdata['identity']->mobile_phone,
            "remark2" => $userdata['identity']->first_name . " " . $userdata['identity']->last_name,
            "remark3" => $userdata['identity']->email,
            "update" => "N",
            "bank_code" => $bank_code,
            "va_expired" => 240,
            "signature" => $generatedSignature
        ];
        
        $form_field = http_build_query($data);
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://sandbox-api.espay.id/rest/merchantpg/sendinvoice",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $form_field,
          CURLOPT_HTTPHEADER => 
            ["Content-Type: application/x-www-form-urlencoded"],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            Storage::disk('local')->put('SendInvVA'.date('Ymd_His').'.json', json_encode($response,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
            $retdata = json_decode($response, true);
            if($retdata['error_code'] == "0000"){
                $this->updateCart($order_id, $retdata["bank_code"], $retdata["va_number"]);
            }
            $retdata = [
                "VA_Number" => $retdata['va_number'],
                "Expired" => $retdata['expired'],
                "Full_User_Name" => $userdata['identity']->first_name . " " . $userdata['identity']->last_name,
            ];
            return $retdata;
        }
    }

    public function inquiry(Request $request)
    {
        // dd(json_encode($request->post(),JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        Storage::disk('local')->put('inquiry'.date('Ymd_His').'.json', json_encode($request->post(),JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));//cek data
        $espay_rq_uuid     = (!empty($request->post('rq_uuid'))     ? $request->post('rq_uuid')     : '');
        $espay_rq_datetime = (!empty($request->post('rq_datetime')) ? $request->post('rq_datetime') : '');
        $espay_member_id   = (!empty($request->post('member_id'))   ? $request->post('member_id')   : '');
        $espay_comm_code   = (!empty($request->post('comm_code'))   ? $request->post('comm_code')   : '');
        $espay_order_id    = (!empty($request->post('order_id'))    ? $request->post('order_id')    : '');
        $espay_password    = (!empty($request->post('password'))    ? $request->post('password')    : '');
        $espay_signature   = (!empty($request->post('signature'))   ? $request->post('signature')   : '');

        //-------------BOF Inquiry DATA
        $orderData = $this->assign_order($espay_order_id);
        // $data_order_id       = $orderData['id_et_ordered_ticket_hd'];
        // $data_total          = $orderData['total_price'];
        $data_currency       = 'IDR';
        //-------------EOF Inquiry DATA        

        $generatedSignature = $this->genSignature($espay_rq_datetime, $espay_order_id, 'INQUIRY');

        if(env('ESPAY_PASSWORD') === $espay_password){
            // if ($generatedSignature == $espay_signature){
                if (!$data_currency) {
                    echo trim('1;Order Id Does Not Exist;;;;;');
                } else {
                    echo(trim("0;Success;" . $espay_order_id . ';' . $orderData . ';' . $data_currency . ';Preembayaran Order ' . $data_currency . ';' . date('Y/m/d H:i:s') . ''));
                }
            // }else{
            //     echo trim('1;Merchant Failed to Identified;;;;;');
            // }
        }else{
            echo trim('1;Merchant Failed to Identified;;;;;');
        }
    }

    public function payment_notif(Request $request)
    {
        Storage::disk('local')->put('notif'.date('Ymd_His').'.json', json_encode($request->post(),JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));//cek data
        $espay_signature        = (!empty($request->post('signature'))      ? $request->post('signature')       : '');
        $espay_rq_datetime      = (!empty($request->post('rq_datetime'))    ? $request->post('rq_datetime')     : '');
        $espay_member_id        = (!empty($request->post('member_id'))      ? $request->post('member_id')       : '');
        $espay_order_id         = (!empty($request->post('order_id'))       ? $request->post('order_id')        : '');
        $espay_password         = (!empty($request->post('password'))       ? $request->post('password')        : '');
        $espay_debit_from       = (!empty($request->post('debit_from'))     ? $request->post('debit_from')      : '');
        $espay_credit_to        = (!empty($request->post('credit_to'))      ? $request->post('credit_to')       : '');
        $espay_credit_to_name   = (!empty($request->post('credit_to_name')) ? $request->post('credit_to_name')  : '');
        $espay_product          = (!empty($request->post('product_code'))   ? $request->post('product_code')    : '');
        $espay_paidAmount       = (!empty($request->post('amount'))         ? $request->post('amount')          : '');
        $espay_paymentfee       = (!empty($request->post('payment_fee'))    ? $request->post('payment_fee')     : '');
        $espay_payment_ref      = (!empty($request->post('payment_ref'))    ? $request->post('payment_ref')     : '');
        // -----------------------------------------------------------------------------------------------------------
        $espay_rq_uuid                  = (!empty($request->post('rq_uuid'))                       ? $request->post('rq_uuid')                       : '');
        $espay_comm_code                = (!empty($request->post('comm_code'))                     ? $request->post('comm_code')                     : '');
        $espay_ccy                      = (!empty($request->post('ccy'))                           ? $request->post('ccy')                           : '');
        $espay_debit_from_name          = (!empty($request->post('debit_from_name'))               ? $request->post('debit_from_name')               : '');
        $espay_message                  = (!empty($request->post('message'))                       ? $request->post('message')                       : '');
        $espay_payment_datetime         = (!empty($request->post('payment_datetime'))              ? $request->post('payment_datetime')              : '');
        $espay_debit_from_bank          = (!empty($request->post('debit_from_bank'))               ? $request->post('debit_from_bank')               : '');
        $espay_credit_to_bank           = (!empty($request->post('credit_to_bank'))                ? $request->post('credit_to_bank')                : '');
        $espay_apprv_code_full_bca      = (!empty($request->post('approval_code_full_bca'))        ? $request->post('approval_code_full_bca')        : '');
        $espay_apprv_code_instllmnt_bca = (!empty($request->post('approval_code_installment_bca')) ? $request->post('approval_code_installment_bca') : '');
        
        $generatedSignature = $this->genSignature($espay_rq_datetime, $espay_order_id, 'PAYMENTREPORT');

        if (env('ESPAY_PASSWORD') === $espay_password) {
             if ($generatedSignature === $espay_signature) {
                // validate order id
                $result = $this->assign_order($espay_order_id);
                if($result == 'false'){
                    echo trim('1,Order Id Does Not Exist,,,');
                }else{
                    if($result >= $espay_paidAmount){
                        $this->payment_success($espay_order_id, $espay_credit_to_bank, $espay_product, $espay_rq_datetime);
                        $reconsile_id = trim($espay_member_id . " - " . $espay_order_id . date('YmdHis'));                    
                        echo trim('0,Success,' . $reconsile_id . ',' . $espay_order_id . ',' . date('Y-m-d H:i:s') . '');
                    }else{
                        echo trim('1,The Amount are less than the way it supposed to be,,,');
                    }
                }
             } else {
                 echo trim('1,Invalid Signature Key,,,');
             }
        } else {
            echo trim('1,Password does not match,,,');
        }
    }

    public function payment_success($OrderID, $BankName=false, $espay_product=false, $paid_at)
    {
        // $OrderID = $request->post('order_id'); 
        // $BankName= $request->post('bank_name'); 
        $check_order = M_Cart_Product_HD::where('id_et_cart_product_hd', $OrderID);
        if($check_order->count()){
            $check_order->update([
                'updated_at' => date('Y-m-d H:i:s'), 
                'is_done' => 'Y',
                'payment_method' => $espay_product,
                'bank_name' => $BankName,
            ]);
            $check_data = M_Ordered_Ticket_Txes::where('id_et_cart_product_hd', $OrderID)
                            ->where('state','Y')
                            ->where('paid_state', 1)
                            ->where('is_active', 'Y')->first();
            if($check_data->count()){
                $check_data->update([
                    'updated_at' => date('Y-m-d H:i:s'),                     
                    'paid_at' => $paid_at,
                    'paid_state' => 2,
                ]);
            }
        }
    }

    public function payment_status(Request $request)
    {
        $data["hasil"] ="OK" ;

        return $data;
    }

    public function payment_status2(Request $request)
    {
        if(!Auth::user()){
            return redirect('/login');
        }

        $userdata['identity'] = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)->first();

        $data = ["id" => $request->post('id')];
        $check_data = M_Ordered_Ticket_Txes::where('id_et_cart_product_hd', $data['id'])
                        ->where('state','Y')
                        ->where('is_active', 'Y')->first();
        $check_order = M_Cart_Product_HD::where('id_et_cart_product_hd', $data['id'])
                        ->where('state','Y')
                        ->where('is_active', 'Y')->first();

        $payment_method = $check_order->payment_method;
        if($payment_method == 'PERMATAATM' ){
            $adminfee = 4400;
        }elseif($payment_method == 'MANDIRIIB'){
            $adminfee = 3300;
        }else{
            $adminfee = 5975;
        }
        $payment = $check_order->account_name;
        if(!$payment){
            $paid_state = 1;
            $state_name = "ON_PROGRESS";
            $expired = $check_order->cc_expired_date;
            $admin_fee = $adminfee;
            $amount = $check_data->total_amount + $adminfee;
        }else{
            $paid_state = 5;
            $state_name = "ON_PROGRESS_BANK";
            $expired = $check_order->cc_expired_date;
            $admin_fee = $adminfee;
            $amount = $check_data->total_amount + $adminfee;
        }
        if(!$check_data){
            $data["state"] = 1;
            $data["state_name"] = "UNKNOWN";
        }else{
            switch($check_data->paid_state){
            case 1:
                    $data["state"] = $paid_state;
                    $data["state_name"] = $state_name;
                    $data["account_number"] = $payment;
                    $data["expired"] = $expired;
                    $data["admin_fee"] = $admin_fee;
                    $data["amount"] = $amount;
                    $data["full_user_name"] = $userdata['identity']->first_name ." ". $userdata['identity']->last_name;
                    break;
            case 2:
                    $data["state"] = $check_data->paid_state;
                    $data["state_name"] = "PAID";
                    break;
            case 3:
                    $data["state"] = $check_data->paid_state;
                    $data["state_name"] = "EXPIRED";
                    break;
            case 4: 
                    $data["state"] = $check_data->paid_state;
                    $data["state_name"] = "CANCEL";
                    break;
            case 9:
                    $data["state"] = $check_data->paid_state;
                    $data["state_name"] = "SHOWED";
                    break;
            default:
                    $data["state"] = 1;
                    $data["state_name"] = "UNKNOWN";
            }
        }
        return $data;
        // return view('frontend.payment-status',$data);
    }

    public function updateCart($OrderID, $bank_code, $bank_rek=false, $payment_method=false)
    {
        $check_order = M_Cart_Product_HD::where('id_et_cart_product_hd', $OrderID);
        if($check_order->count()){
            $check_order->update([
                'updated_at' => date('Y-m-d H:i:s'),
                'bank_name' => $bank_code,
                'account_name' => $bank_rek,
                'payment_method' => $payment_method,
            ]);
        }
    }

    public function get_order_value($OrderID)
    {
        $cart = M_Cart_Product_HD::where('cd_et_cart_product_hd', $OrderID)
                ->with(['toCartProductDt' => function($query){
                    $query->where('is_active', 'Y');
                    $query->where('state', 'Y');
                }])->first();
        $check_order = M_Cart_Product_DT::where('id_et_cart_product_hd', $cart->id_et_cart_product_hd)
        ->where('is_Active', 'Y')
        ->sum('existing_idem_price');
        return $check_order.".00";
    }

    public function assign_order($OrderID)
    {
        // $OrderID = $request->post('cd_p');
        $cart = M_Cart_Product_HD::where('id_et_cart_product_hd', $OrderID)
                ->where('state','Y')
                ->where('is_active', 'Y')
                ->where('is_done', 'N')->first();
        if(!$cart){
            return "false";
        }
        $check_order = M_Cart_Product_BD::where('id_et_cart_product_hd', $cart['id_et_cart_product_hd'])->get();        
        $payment_method = $cart->payment_method;
        if($payment_method == 'PERMATAATM' ){
            $adminfee = 4400;
        }elseif($payment_method == 'MANDIRIIB'){
            $adminfee = 3300;
        }else{
            $adminfee = 5975;
        }
        if($check_order->count()){
            return $check_order->sum('total_amount') + $adminfee;
        }else{
            return "false";
        }
    }

    //--------------------------------smua masih disini. will be moved to respected files and locations---EOF

    public function getPrice($date, $cd, $qty){

        $promo = MV_Promo::where('m_promotion_uu', $cd)->first();

        $calendar = M_Event_Calendar::where('state', 'Y')->where('is_active', 'Y')->where('event_startdate', '<=', $date)->where('event_enddate', '>=', $date)->get();
        
        $get_result_cal = '';
        if($calendar->count()){
            foreach($calendar as $cal1){
                if($cal1->event_type == 'WEEK'){
                    $get_result_cal = 'WEEK';
                    break;
                }elseif($cal1->event_type == 'HOLIDAY'){
                    $get_result_cal = 'HOLIDAY';
                }
            }
        }else{
            $weekEnd = date('w', strtotime($date));
            if($weekEnd == 0 || $weekEnd == 6){
                $get_result_cal = 'HOLIDAY';
            }else{
                $get_result_cal = 'WEEKDAYS';
            }
        }
        $result['qty'] = $qty;
        $result['minval'] = $promo->min_val;

        $check_qty = qty_validate($promo, $qty);
        if(isset($check_qty['state'])){
            if($check_qty['state'] == 0){
                return $check_qty;
            }else{
                $qty_fix = $check_qty['result'];
            }
        }else{
            return $check_qty;
        }
        if($get_result_cal == 'WEEK'){
            $result['amountraw'] = $promo->pekan_value;            
            $result['total_amountraw'] = $promo->pekan_value * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format( $promo->pekan_value,0,',','.');
            $result['total_product'] = 'Rp. ' . number_format( $promo->totalproductprice_pekan,0,',','.');
            $result['promo_type'] =  $promo->promo_pekan_type;
            $result['promo_amount'] = 'Rp. ' . number_format( $promo->promo_pekan,0,',','.');
        }elseif($get_result_cal == 'HOLIDAY'){
            $result['amountraw'] = $promo->holiday_value;
            $result['total_amountraw'] = $promo->holiday_value * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format( $promo->holiday_value,0,',','.');
            $result['total_product'] = 'Rp. ' . number_format( $promo->totalproductprice_holiday,0,',','.');
            $result['promo_type'] =  $promo->promo_holiday_type;
            $result['promo_amount'] = 'Rp. ' . number_format( $promo->promo_holiday,0,',','.');
        }elseif($get_result_cal == 'WEEKDAYS'){
            $result['amountraw'] = $promo->weekdays_value;
            $result['total_amountraw'] = $promo->weekdays_value * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format($promo->weekdays_value,0,',','.');
            $result['total_product'] =  'Rp. ' . number_format($promo->totalproductprice_weekdays,0,',','.');
            $result['promo_type'] = $promo->promo_weekdays_type;
            $result['promo_amount'] = 'Rp. ' . number_format( $promo->promo_weekdays,0,',','.');
        }

        return $result;
    }

    public function getPriceProduct($date, $cd, $qty){

        $product = MV_Product::where('m_product_uu', $cd)->first();

        $calendar = M_Event_Calendar::where('state', 'Y')->where('is_active', 'Y')->where('event_startdate', '<=', $date)->where('event_enddate', '>=', $date)->get();
        
        $get_result_cal = '';
        if($calendar->count()){
            foreach($calendar as $cal1){
                if($cal1->event_type == 'WEEK'){
                    $get_result_cal = 'WEEK';
                    break;
                }elseif($cal1->event_type == 'HOLIDAY'){
                    $get_result_cal = 'HOLIDAY';
                }
            }
        }else{
            $weekEnd = date('w', strtotime($date));
            if($weekEnd == 0 || $weekEnd == 6){
                $get_result_cal = 'HOLIDAY';
            }else{
                $get_result_cal = 'WEEKDAYS';
            }
        }
        $result['qty'] = $qty;
        $qty_fix = $qty;

        if($get_result_cal == 'WEEK'){
            $result['amountraw'] = $product->productprice_pekan;            
            $result['total_amountraw'] = $product->productprice_pekan * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format( $product->productprice_pekan,0,',','.');
            $result['total_product'] = 'Rp. ' . number_format( $product->productprice_pekan,0,',','.');
            $result['product_type'] =  $product->product_category;
            $result['product_amount'] = 'Rp. ' . number_format( $product->productprice_pekan,0,',','.');
        }elseif($get_result_cal == 'HOLIDAY'){
            $result['amountraw'] = $product->productprice_holiday;
            $result['total_amountraw'] = $product->productprice_holiday * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format( $product->productprice_holiday,0,',','.');
            $result['total_product'] = 'Rp. ' . number_format( $product->productprice_holiday,0,',','.');
            $result['product_type'] =  $product->product_category;
            $result['product_amount'] = 'Rp. ' . number_format( $product->productprice_holiday,0,',','.');
        }elseif($get_result_cal == 'WEEKDAYS'){
            $result['amountraw'] = $product->productprice_weekdays;
            $result['total_amountraw'] = $product->productprice_weekdays * $qty_fix;
            $result['amount'] = 'Rp. ' . number_format($product->productprice_weekdays,0,',','.');
            $result['total_product'] =  'Rp. ' . number_format($product->productprice_weekdays,0,',','.');
            $result['product_type'] = $product->product_category;
            $result['product_amount'] = 'Rp. ' . number_format( $product->productprice_weekdays,0,',','.');
        }

        return $result;
    }


    public function testEmailControllerLain(){

        //$controller_lain = app('App\Http\Controller\Frontend\TmiiEtOrderedTicketTxController');

        $a = new TmiiEtOrderedTicketTxController ;
        $a->sendEmail();
    }

}
