<?php

namespace App\Http\Controllers\Frontend;

// use App\Models\tmii_et_ordered_ticket_tx;
use App\Models\M_Ordered_Ticket_Txes;
use App\Models\M_Cart_Product_HD;
use App\Models\M_Cart_Product_BD;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use App\Models\UnpaidCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Http\Requests;

//use App\Mail\MailUnpaid;
use DateTime;
use DB;
use PDF ;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Mail;


class TmiiEtOrderedTicketTxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = M_Ordered_Ticket_Txes::All();
        return $order;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kode = $request->post('id');
        $payment_method = $request->post('payment_method');
        $cart = M_Cart_Product_HD::where('id_et_cart_product_hd', $kode)
                ->where('state','Y')
                ->where('is_active', 'Y')
                ->where('is_done', 'N')->first();
        if($cart->count()){
            if($payment_method != 'PERMATAATM'){
                $cart->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'payment_method' => $payment_method,
                ]);
            }else{
                $cart->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'payment_method' => $payment_method,
                    'is_done' => 'Y',
                ]);
            }
        }
        $value = M_Cart_Product_BD::where('id_et_cart_product_hd', $cart['id_et_cart_product_hd'])
                ->where('state','Y')
                ->where('is_active', 'Y')->get();
        $id_cart_hd = $cart['id_et_cart_product_hd'];//dd($id_cart_hd);
        $total_amount = $value->sum('total_amount');
        $check_data = M_Ordered_Ticket_Txes::where('id_et_cart_product_hd', $id_cart_hd)
                ->where('state','Y')
                ->where('is_active', 'Y')->first();//dd($check_data);
        if(($check_data != null) and ($check_data->count() > 0)){
            $check_data->deleted_at = date('Y-m-d H:i:s');
            $check_data->deleted_by = 1;
            $check_data->is_active = 'N';
            $check_data->save();
            // ->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => 'System', 'is_active' => 'N' ]);
        }

        $order = new M_Ordered_Ticket_Txes;
        $order->cd_et_ordered_ticket_txes = get_prefix('et_ordered_ticket_txes');
        $order->id_et_cart_product_hd = $id_cart_hd;
        $order->total_amount = $total_amount;
        $result = $order->save();
        // Mail::to(Auth::guard('customer')->user()->email)->queue(new MailUnpaid);

//        $this->sendEmail($total_amount);
        $this->sendmail( $id_cart_hd,$request->sub_total,$request->biaya_admin);

//        $con = new EticketingController();
//        $va = $con->sendInv2();

//        return (String)$result;
        $retdata = [
            "kode" => $kode,
            "id_cart" => $cart['id_et_cart_product_hd'],
            "amount" => $total_amount,
        ];
        return $retdata;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\M_Ordered_Ticket_Txes  $M_Ordered_Ticket_Txes
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $order = M_Ordered_Ticket_Txes::find($request->post('id'));
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\M_Ordered_Ticket_Txes  $M_Ordered_Ticket_Txes
     * @return \Illuminate\Http\Response
     */
    public function edit(M_Ordered_Ticket_Txes $M_Ordered_Ticket_Txes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\M_Ordered_Ticket_Txes  $M_Ordered_Ticket_Txes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, M_Ordered_Ticket_Txes $M_Ordered_Ticket_Txes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\M_Ordered_Ticket_Txes  $M_Ordered_Ticket_Txes
     * @return \Illuminate\Http\Response
     */
    // public function destroy(M_Ordered_Ticket_Txes $M_Ordered_Ticket_Txes)
    // {
    //     //
    // }

//    refrence: https://code.tutsplus.com/id/tutorials/how-to-send-emails-in-laravel--cms-30046
    public function sendEmail()
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'ATM - Invoice Pembayaran';
        $objDemo->demo_two = 'Time Sent: '.date('Y-m-d H:i:s') ;
        $objDemo->sender = 'TMII';
        $objDemo->receiver = 'ReceiverUserName';

        Mail::to("rianhariadi@gmail.com")->send(new DemoEmail($objDemo));

        return 'Email has sent at: '.date('Y-m-d H:i:s') ;
    }


//    diambil dari MailUnpaid.php
    public function build(Request $request)
    {
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
        $subtotal = $request->sub_total;
        $biaya_admin = $request->biaya_admin;
        $customerData = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::guard('customer')->user()->id_us_frontend_hd)->first();
        $pdf = PDF::loadView('PDF.invoice', compact('carts', 'subtotal', 'biaya_admin', 'customerData'));
        // return $pdf->stream();
        return $this->view('frontend.cart.pmt_rules', compact('carts', 'customerData'))
            ->subject("[E-TICKETING TMII] Petunjuk Pembayaran E-Tiket")
            ->attachData($pdf->output(), "unpaid.pdf");
    }


    public function sendmail($cart_id=0,$sub_total,$biaya_admin){

        $data['email'] = Auth::guard('customer')->user()->email;
        $customerData = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::guard('customer')->user()->id_us_frontend_hd)->first();
        $check = M_Ordered_Ticket_Txes::where('id_et_cart_product_hd', $cart_id)->first();
        $data['first_name'] = $customerData->first_name ;
        $data['last_name'] = $customerData->last_name ;
        $data['mobile_phone'] = $customerData->mobile_phone ;
        $data['sub_total'] =  $check->total_amount ;
        $data['biaya_admin'] = $biaya_admin;


        $data['total_amount'] = $sub_total;

        $data["client_name"]='Costumer TMII';
        $data["subject"]= 'INVOICE TICKET TMII No.'.$cart_id;
        $data['cart_id'] = $cart_id ;
//        $check =   $check_data = M_Ordered_Ticket_Txes::where('id_et_cart_product_hd', $cart_id)->get() ;


        $pdf = PDF::loadView('mail.pdf_invoice',$data);

        try{
            Mail::send('mail.pdf_invoice', $data, function($message)use($data,$pdf) {
                $message->to($data["email"], $data["client_name"])
                    ->from('tmii.reservation@gmail.com', 'TMII Management')
                    ->sender('tmii.reservation@gmail.com', 'TMII Management')
                    ->subject($data["subject"])
                    ->attachData($pdf->output(), "invoice.pdf");
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";

        }else{

            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }
        // return response()->json(compact('this'));
    }


    public function cobaakses(){

        $result = EticketingController::mystatic(4);


        dd($result) ;
    }



}
