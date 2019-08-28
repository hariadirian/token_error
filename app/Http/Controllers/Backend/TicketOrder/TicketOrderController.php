<?php

namespace App\Http\Controllers\Backend\TicketOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Ms_Paid_State;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Mail\MailUnpaid;
use PDF;
use App\Models\UnpaidCart;
use App\Models\M_Cart_Product_HD;

class TicketOrderController extends Controller
{

    public function index(){

        $data['paid_state']  = M_Ms_Paid_State::where('state', 'Y')->orderBy('created_at', 'desc')->pluck('paid_state_name', 'id_ms_paid_state');

        return view('backend.ticket_order.ordered_ticket', $data);
    }

    public function EmailUnpaid(Request $request)
    {
      $total_tikets = $request->total_tiket;
      $ticket_names = $request->ticket_name;
      $ticket_qtys = $request->ticket_qty;
      $id_et_cart_product_bds = $request->id_et_cart_product_bd;
      $id_us_frontend_hds = $request->id_us_frontend_hd;
      if ($request->has('diskon')) {
        $diskons = $request->diskons;
      }
      else {
        $diskon = $request->diskon;
      }

      for($i = 0 ; $i < count($total_tikets) ; $i++)
      {
         $total_tiket = $total_tikets[$i];
         $ticket_name = $ticket_names[$i];
         $ticket_qty = $ticket_qtys[$i];
         $id_et_cart_product_bd = $id_et_cart_product_bds[$i];
         if ($request->has('diskon')) {
           $diskon = $diskons[$i];
         }
         else {
           $diskon = $request->diskon;
         }

         $new = new UnpaidCart;
         $new->id_us_frontend_hd = $request->id_us_frontend_hd;
         $new->id_et_cart_product_bd = $id_et_cart_product_bd;
         $new->ticket_name = $ticket_name;
         $new->total_tiket = $total_tiket;
         $new->ticket_qty = $ticket_qty;
         // if ($request->has('diskon')->count() > 1) {
           $new->diskon = $diskon;
         // }
         // else {
         //   $new->diskon = $request->diskon;
         // }
         $new->created_at = date('Y-m-d H:i:s');
         $new->save();
      }

      Mail::to(Auth::guard('customer')->user()->email)->queue(new MailUnpaid);
      return redirect('/');
    }

}
