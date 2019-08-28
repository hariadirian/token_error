<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use PDF;
use App\Models\UnpaidCart;
use App\Models\M_Cart_Product_HD;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use App\Services\ProductService;


class MailUnpaid extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
      //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
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
      $unpaids = UnpaidCart::where('id_us_frontend_hd', $request->id_us_frontend_hd)->where('created_at', date('Y-m-d H:i:s'))->get();
      $customerData = M_Us_Frontend_DT::where('id_us_frontend_hd', Auth::guard('customer')->user()->id_us_frontend_hd)->first();
      $pdf = PDF::loadView('PDF.unpaid', compact('carts', 'unpaids', 'subtotal', 'biaya_admin', 'customerData'));
      // return $pdf->stream();
      return $this->view('frontend.cart.pmt_rules', compact('carts', 'customerData'))
                  ->subject("[E-TICKETING TMII] Petunjuk Pembayaran E-Tiket")
                  ->attachData($pdf->output(), "unpaid.pdf");
    }
}
