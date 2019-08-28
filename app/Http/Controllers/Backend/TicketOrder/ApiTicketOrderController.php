<?php

namespace App\Http\Controllers\Backend\TicketOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Ordered_Ticket_Txes;
use App\Models\M_Generated_Ticket_DT;
use App\Models\M_Redeemed_Ticket;
use App\Models\M_Idempiere\M_View\MV_Product;
use Auth;
use DB;
use Session;

class ApiTicketOrderController extends Controller
{

    public function getDataTable(Request $request){

        if ($request->additional_filter) {
            foreach ($request->additional_filter as $par) {
                $filter[$par['name']] = $par['value'];
            }
        }
        // MAIN QUERY
        $datas = M_Ordered_Ticket_Txes::leftJoin('tmii_et_generated_ticket_hd as d',
                    'tmii_et_ordered_ticket_txes.id_et_ordered_ticket_txes', '=', 'd.id_et_ordered_ticket_txes')
                ->leftJoin('tmii_et_cart_product_hd as f',
                'tmii_et_ordered_ticket_txes.id_et_cart_product_hd', '=', 'f.id_et_cart_product_hd')
                ->leftJoin('us_frontend_hd as e',
                'f.id_us_frontend_hd', '=', 'e.id_us_frontend_hd')
                ->leftJoin('tmii_ms_paid_state as g',
                'tmii_et_ordered_ticket_txes.paid_state', '=', 'g.id_ms_paid_state')
                ->where('tmii_et_ordered_ticket_txes.state', 'Y')->orderBy('created_at', 'desc');

        if (isset($filter['paid_state'])) {
            $datas->where('paid_state', $filter['paid_state']);
        }
        if(isset($filter['current'])){
            if($filter['current'] == 'on'){
                $datas->where('tmii_et_ordered_ticket_txes.is_active', 'Y');
            }else{
                $datas->where('tmii_et_ordered_ticket_txes.is_active', 'N');
            }
        }else{
            $datas->where('tmii_et_ordered_ticket_txes.is_active', 'N');
        }

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                $q->orWhere('cd_et_ordered_ticket_txes', 'LIKE', '%'.$search.'%');
                $q->orWhereRaw('to_char(paid_at, \'YYYY-MM-DD HH:ii:ss\') LIKE '.'\'%'.$search.'%\'');
                $q->orWhereRaw('to_char(email_sent_at, \'YYYY-MM-DD HH:ii:ss\') LIKE '.'\'%'.$search.'%\'');
                $q->orWhere('d.created_at', 'LIKE', '%'.$search.'%');
                $q->orWhere('g.paid_state_name', 'LIKE', '%'.$search.'%');
            });
        }

        $sortBy = $request->order[0]['dir'];
        //ORDER BY substr($data->created_at, 11, 8)
        if($request->order[0]['column'] == 0){
            $column = 'd.created_at';
            $sortBy = 'DESC';
        }elseif($request->order[0]['column'] == 1){
            $column = 'no';
        }elseif($request->order[0]['column'] == 2){
            $column = 'cd_et_ordered_ticket_txes';
        }elseif($request->order[0]['column'] == 5){
            $column = 'g.paid_state_name';
        }elseif($request->order[0]['column'] == 6){
            $column = 'd.created_at';
        }else{
            $column = 'no';
        }

        $datas->select(DB::raw('@row:=@row+1 AS no'), 'tmii_et_ordered_ticket_txes.id_et_ordered_ticket_txes','tmii_et_ordered_ticket_txes.id_et_cart_product_hd','tmii_et_ordered_ticket_txes.cd_et_ordered_ticket_txes', 'tmii_et_ordered_ticket_txes.created_at', 'd.created_at as generate_created_at', 'paid_at', 'g.paid_state_name', 'e.email');

        $datas->orderBy($column, $sortBy);
        
        //FILTER PAGINATION & LIMIT
        $total = $datas->get()->count();
        if($request->length != -1){
            $datas->limit($request->length)->offset($request->start);
        }

        //READY TO PASSING !
        DB::statement(DB::raw('set @row:=0'));
        $datas = $datas->get();

        $rows   = array();
        $x      = 0;
       if($datas){
            foreach($datas as $key => $data){
                if ($data->toCartProductHd) {
                    $cartHd         = $data->toCartProductHd->first();
                    // DB::enableQueryLog();
                    $summaryCartHd  = $cartHd->summaryCartHd($data->id_et_cart_product_hd)->first();
                    $product = '';
                    if ($summaryCartHd) {
                        if ($summaryCartHd->qty_regular and $summaryCartHd->qty_promo) {
                            $product = 'Regular: '.$summaryCartHd->qty_regular.', Promo: '.$summaryCartHd->qty_promo;
                        } elseif ($summaryCartHd->qty_regular) {
                            $product = 'Regular: '.$summaryCartHd->qty_regular;
                        } elseif ($summaryCartHd->qty_promo) {
                            $product = 'Promo: '.$summaryCartHd->qty_promo;
                        }
                    }
                    $rows[$x][0] = $data->cd_et_ordered_ticket_txes;
                    $rows[$x][1] = $data->no;
                    $rows[$x][2] = $data->cd_et_ordered_ticket_txes;
                    $rows[$x][3] = $product;
                    $rows[$x][4] = isset($summaryCartHd->total_amount)? 'Rp. ' . number_format($summaryCartHd->total_amount, 0, ',', '.').',-' : '';
                    $rows[$x][5] =  $data->paid_state_name;
                    $rows[$x][6] =  $data->created_at;

                    $x++;
                }
            }
       }

        $result['aaData'] = $rows;
        $result['iTotalRecords'] = $total;
        $result['iTotalDisplayRecords'] = $total;
        return $result;

    }

    public function getDetailDataTable(Request $request){

        $tickets  =   M_Ordered_Ticket_Txes::where('cd_et_ordered_ticket_txes', $request->uniqcd)->get();

        $data = [
            'tickets' => $tickets
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.ticket_order.datatable-detail-ticketorder', $data) );

        return json_encode(array($modal_content));
    }

    public function storeProduct(Request $request){

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('public/img/product');

        $ticketHD = M_Ticket_Img_HD::where('idem_ticket_uu', $request->idem_ticket_uu);

        if($ticketHD->count()){
            $ticketHDId = $ticketHD->first()->id_ms_ticket_img_hd;
        }else{
            $ticketHDData = M_Ticket_Img_HD::create([
                'cd_ms_ticket_img_hd'   => get_prefix('ms_ticket_img_hd'),
                'idem_ticket_uu'        => $request->idem_ticket_uu,
                'type_ticket'           => 'PRODUCT',
                'created_by'            => Auth::guard('administrator')->user()->id_us_backend_hd
            ]);
            $ticketHDId =  $ticketHDData->id_ms_ticket_img_hd;
        }

        $ticketHDData = M_Ticket_Img_DT::create([
            'cd_ms_ticket_img_dt'   => get_prefix('ms_ticket_img_dt'),
            'id_ms_ticket_img_hd'   => $ticketHDId,
            'filename'              => $uploadedFile->getClientOriginalName(),
            'srcname'               => $path,
            'img_type'              => $request->img_type,
            'created_by'            => Auth::guard('administrator')->user()->id_us_backend_hd
        ]);
    }

    public function scanningTicket(Request $request){

        $ticket  = M_Generated_Ticket_DT::where('state', 'Y')->where('is_active', 'Y')->where('cd_et_generated_ticket_dt', $request->cd)->first();
        if(!$ticket){
            return json_encode(['state' => 0, 'message' => 'Ticket is not found! Check the QR Code or ask administrator for more information']);
        }
        //this shit need tuning!
        $org_authorized = Auth::guard('administrator')->user()
                        ->toUsBackendOrganizationUser()
                        ->leftJoin('us_backend_organizations as b',
                            'us_backend_organization_user.id_us_backend_organization', '=', 'b.id_us_backend_organization')
                        ->where('us_backend_organization_user.state', 'Y')
                        ->where('us_backend_organization_user.is_active', 'Y')
                        ->where('b.state', 'Y')
                        ->where('b.is_active', 'Y')
                        ->pluck('m_attributeset_uu');

        $ticket_cart = $ticket->toCartProductDt->whereIn('m_attributeset_uu', $org_authorized)->first();

        if(!$ticket_cart){
            return json_encode(['state' => 0, 'message' => 'Ticket is found! But you have no authorization to scanning this ticket. Check the ticket and make sure you are in the right vehicle!']);
        }

        $redeem = M_Redeemed_Ticket::where('state', 'Y')
                        ->where('is_active', 'Y')
                        ->where('id_et_generated_ticket_dt', $ticket->id_et_generated_ticket_dt)->get();

        $product = MV_Product::where('attributeset_uu', $ticket_cart->m_attributeset_uu)->first();

        $data = [
            'ticket' => $ticket,
            'redeem' => $redeem,
            'ticket_cart' => $ticket_cart,
            'product' => $product,
            'order' => $ticket->toGeneratedTicketHd->toOrderedTicketTxes,
        ];
        $modal_content = preg_replace( "/\r|\n/", "", view('backend.ticket.scan-result', $data) );

        return json_encode([
            'state' => 1,
            'content' => $modal_content
        ]);
    }

    public function redeemTicket(Request $request){

        $ticket  = M_Generated_Ticket_DT::where('state', 'Y')->where('is_active', 'Y')->where('cd_et_generated_ticket_dt', $request->cd)->first();

        if(!$ticket){
            return json_encode(['state' => 0, 'message' => 'Ticket is not found! Check the QR Code or ask administrator for more information']);
        }

        $redeemed = M_Redeemed_Ticket::where('state', 'Y')
                        ->where('is_active', 'Y')
                        ->where('id_et_generated_ticket_dt', $ticket->id_et_generated_ticket_dt);

        if($ticket->qty - ($redeemed->sum('qty_redeemed') + $request->total) < 0){
            return json_encode(['state' => 0, 'message' => 'Total redeem tickets exceeding purchase limit!']);
        }

        M_Redeemed_Ticket::create([
            'cd_et_redeemed_ticket'     => get_prefix('et_redeemed_ticket'),
            'id_et_generated_ticket_dt' => $ticket->id_et_generated_ticket_dt,
            'qty_redeemed'              => $request->total,
            'created_by'                => Auth::guard('administrator')->user()->id_us_backend_hd
        ]);

        //this shit need tuning!
        $org_authorized = Auth::guard('administrator')->user()
                        ->toUsBackendOrganizationUser()
                        ->leftJoin('us_backend_organizations as b',
                            'us_backend_organization_user.id_us_backend_organization', '=', 'b.id_us_backend_organization')
                        ->where('us_backend_organization_user.state', 'Y')
                        ->where('us_backend_organization_user.is_active', 'Y')
                        ->where('b.state', 'Y')
                        ->where('b.is_active', 'Y')
                        ->pluck('m_attributeset_uu');

        $ticket_cart = $ticket->toCartProductDt->whereIn('m_attributeset_uu', $org_authorized)->first();

        if(!$ticket_cart){
            return json_encode(['state' => 0, 'message' => 'Ticket is found! But you have no authorization to scanning this ticket. Check the ticket and make sure you are in the right vehicle!']);
        }

        $redeem = M_Redeemed_Ticket::where('state', 'Y')
                        ->where('is_active', 'Y')
                        ->where('id_et_generated_ticket_dt', $ticket->id_et_generated_ticket_dt)->get();

        $product = MV_Product::where('m_product_uu', $ticket_cart->m_product_uu)->first();

        $data = [
            'ticket' => $ticket,
            'redeem_state' => 1,
            'redeem' => $redeem,
            'ticket_cart' => $ticket_cart,
            'product' => $product,
            'order' => $ticket->toGeneratedTicketHd->toOrderedTicketTxes,
        ];
        $modal_content = preg_replace( "/\r|\n/", "", view('backend.ticket.scan-result', $data) );

        return json_encode([
            'state' => 1,
            'content' => $modal_content
        ]);
    }
}
