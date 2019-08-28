<?php

namespace App\Http\Controllers\Backend\Ticket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Auth;
use DB;
use App\Models\M_Generated_Ticket_HD;

class TicketController extends Controller
{
    public function __construct() {
    }

    public function index(){
        return view('backend.ticket.ticket');
    }

    public function regenerate(TicketService $ticket, Request $request){
        return $ticket->generate($request->cd_ordered_ticket);
    }
    
    public function scan(){
        return view('backend.ticket.scan');
    }
    
    public function download(Request $request){
        
        $file = M_Generated_Ticket_HD::where('cd_et_generated_ticket_hd', $request->cd_generated_ticket)->first();
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download(storage_path('app/ticket/' . $file->file_path), 'filename.pdf', $headers);
    }

    public function temporary_process_paid_all_cart_order(){
        $asd = DB::select("select
                a.*,
                max(c.paid_state) paid_state,
                sum(b.total_amount) total_amount_2
            from
                tmii_et_cart_product_hd a
            left join tmii_eticketing.tmii_et_cart_product_bd b
            on a.id_et_cart_product_hd = b.id_et_cart_product_hd
            left join tmii_eticketing.tmii_et_ordered_ticket_txes c on
                c.id_et_cart_product_hd = a.id_et_cart_product_hd
            where b.state = 'Y' and b.is_active = 'Y' and a.state = 'Y' and a.is_active = 'Y' 
            and (a.is_done = 'N' or c.paid_state = 1)
            group by a.id_et_cart_product_hd");

            foreach($asd as $qwe){
                if ($qwe->paid_state) {
                    DB::table('tmii_et_ordered_ticket_txes')->update(
                        array(
                            'paid_state' => '2')
                    );
                }else{
                    DB::table('tmii_et_ordered_ticket_txes')->insert(
                        array('cd_et_ordered_ticket_txes' => get_prefix('et_ordered_ticket_txes'),
                            'id_et_cart_product_hd' => $qwe->id_et_cart_product_hd,
                            'total_amount' => $qwe->total_amount_2,
                            'paid_state' => '2')
                    );
                }
            }

                DB::table('tmii_et_cart_product_hd')->update(
                    array('is_done' => 'Y')
                );
            
    }
    
}
