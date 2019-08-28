<?php

namespace App\Http\Controllers\Backend\ReportSales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Cart_Product_HD;
use Auth;
use DB;

class ApiReportSalesController extends Controller
{

    public function getDataTable(Request $request){

        if ($request->additional_filter) {
            foreach ($request->additional_filter as $par) {
                $filter[$par['name']] = $par['value'];
            }
        }
        
        if($filter['date_from']){
            $myDateTime = \DateTime::createFromFormat('D, d M Y', $filter['date_from']);
            $date_from = $myDateTime->format('Y-m-d');
        }else{
            $date_from = date('2018-m-d');
        }

        if($filter['date_to']){
            $myDateTime = \DateTime::createFromFormat('D, d M Y', $filter['date_to']);
            $date_to = $myDateTime->format('Y-m-d');
        }else{
            $date_to = date('Y-m-d');
        }

        // MAIN QUERY
        $datas =  M_Cart_Product_HD::leftJoin('tmii_et_cart_product_bd as b', function($join){
            $join->on('tmii_et_cart_product_hd.id_et_cart_product_hd', '=', 'b.id_et_cart_product_hd');
            $join->where('b.is_active', '=', 'Y');
            $join->where('b.state', '=', 'Y');
        })->leftJoin('tmii_et_cart_product_dt as c', function($join){
            $join->on('b.id_et_cart_product_bd', '=', 'c.id_et_cart_product_bd');
            $join->where('c.is_active', '=', 'Y');
            $join->where('c.state', '=', 'Y');
        })->leftJoin('tmii_et_ordered_ticket_txes as d', function($join){
            $join->on('tmii_et_cart_product_hd.id_et_cart_product_hd', '=', 'd.id_et_cart_product_hd');
            $join->where('d.state', '=', 'Y');
        })->leftJoin('tmii_et_generated_ticket_dt as e', function($join){
            $join->on('c.id_et_cart_product_dt', '=', 'e.id_et_cart_product_dt');
            $join->where('e.is_active', '=', 'Y');
            $join->where('e.state', '=', 'Y');
        })->leftJoin(DB::raw("
                (
                    select
                        id_et_generated_ticket_dt,
                        sum(qty_redeemed) qty_redeemed
                    from
                        tmii_et_redeemed_ticket
                    where
                        is_active = 'Y'
                        and state = 'Y'
                    group by
                        id_et_generated_ticket_dt) as f
            "), function($join){
            $join->on('e.id_et_generated_ticket_dt', '=', 'f.id_et_generated_ticket_dt');
            $join->where('e.is_active', '=', 'Y');
            $join->where('e.state', '=', 'Y');
        })->leftJoin('us_frontend_hd as g', function($join){
            $join->on('g.id_us_frontend_hd', '=', 'tmii_et_cart_product_hd.created_by');
            $join->where('g.state', '=', 'Y');
        })->leftJoin('us_frontend_dt as h', function($join){
            $join->on('g.id_us_frontend_hd', '=', 'h.id_us_frontend_hd');
            $join->where('h.state', '=', 'Y');
        })->leftJoin('us_backend_organizations as i', function($join){
            $join->on('i.m_attributeset_uu', '=', 'c.m_attributeset_uu');
            $join->where('i.state', '=', 'Y');
        });
        if($filter['calendar_filter']){
            if($filter['calendar_filter'] == 'event'){
                $datas->leftJoin('tmii_ms_event_calendar as j', function($join){
                    $join->where('j.state', '=', 'Y');
                    $join->where('j.is_active', '=', 'Y');
                });
            }
        }
        $datas->where(  'tmii_et_cart_product_hd.state',      '=', 'Y')
        ->where(    'tmii_et_cart_product_hd.is_active',  '=', 'Y');

        $datas->where('d.created_at', '<=', $date_to);
        $datas->where('d.created_at', '>=', $date_from);
        $datas->where('tmii_et_cart_product_hd.is_done', 'Y');

        if($filter['state_filter']){
            if($filter['state_filter'] == 'on_progress'){
                $datas->where('d.paid_state', 1);
            }elseif($filter['state_filter'] == 'paid'){
                $datas->where('d.paid_state', 2);
            }elseif($filter['state_filter'] == 'visited'){
                $datas->where('d.paid_state', 2);
                $datas->where('f.qty_redeemed', '>', 0);
            }elseif($filter['state_filter'] == 'cancelled'){
                $datas->where('d.paid_state', 1);
                $datas->where(DB::raw("DATE_ADD(DATE(b.ticket_date), INTERVAL 1 DAY)"), '<=', date('Y-m-d'));
            }elseif($filter['state_filter'] == 'expired'){
                $datas->where('d.paid_state', 2);
                $datas->whereNull('f.qty_redeemed');
                $datas->where(DB::raw("DATE_ADD(DATE(b.ticket_date), INTERVAL 10 DAY)"), '<=', date('Y-m-d'));
            }
        }
        if($filter['wahana_filter']){
            $datas->where('i.cd_us_backend_organization', $filter['wahana_filter']);
        }
        if($filter['cd_order']){
            $datas->where('d.cd_et_ordered_ticket_txes', 'LIKE', '%'.$filter['cd_order'].'%');
        }
        if($filter['calendar_filter']){
            if($filter['calendar_filter'] == 'weekday'){
                $datas->where(DB::raw("WEEKDAY(b.ticket_date)"), '<', 5);
            }elseif($filter['calendar_filter'] == 'weekend'){
                $datas->where(DB::raw("WEEKDAY(b.ticket_date)"), '>=', 5);
            }elseif($filter['calendar_filter'] == 'event'){
                $datas->whereRaw("b.ticket_date BETWEEN j.event_startdate AND j.event_enddate");
            }
        }

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                // $q->orWhere('description',                      'LIKE', '%'.$search.'%');
                // $q->orWhereRaw('to_char(startdate, \'YYYY-MM-DD\') LIKE '.'\'%'.$search.'%\'');
                // $q->orWhereRaw('to_char(enddate, \'YYYY-MM-DD\') LIKE '.'\'%'.$search.'%\'');
                // $q->orWhere('min_val',                          'LIKE', '%'.$search.'%');
            });
        }

        //ORDER BY substr($data->created_at, 11, 8)
        if($request->order[0]['column'] == 0){
            $column = 'no';
        }
        // elseif($request->order[0]['column'] == 1){
        //     $column = 'm_promotion_id';
        // }elseif($request->order[0]['column'] == 2){
        //     $column = 'description';
        // }elseif($request->order[0]['column'] == 3){
        //     $column = 'startdate';
        // }elseif($request->order[0]['column'] == 4){
        //     $column = 'enddate';
        // }elseif($request->order[0]['column'] == 5){
        //     $column = 'min_val';
        // }else{
        //     $column = 'no';
        // }

        $datas->select(DB::raw('@row:=@row+1 AS no'),	'd.created_at as ordered_at', 'd.paid_at', 'd.cd_et_ordered_ticket_txes', 'h.first_name', 'h.last_name', 'h.mobile_phone', 'g.email', 'b.product_name', 'c.ticket_name', 'c.qty_ticket', DB::raw('COALESCE(f.qty_redeemed, 0) as qty_redeemed'), 'tmii_et_cart_product_hd.payment_method', 'tmii_et_cart_product_hd.account_name', 'd.total_amount', 'd.id_et_ordered_ticket_txes', 'b.ticket_date');

        $datas->orderBy($column, $request->order[0]['dir']);
        
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
       
        foreach($datas as $key => $data){
//
            $rows[$x][0] = $data->id_et_ordered_ticket_txes;
            $rows[$x][1] = $data->no;
            $rows[$x][2] = '<b>Ordered At</b> <br />' .substr($data->ordered_at, 0, 10) . '<br />' .substr($data->ordered_at, 11, 10) . '<br /><br /> <b>Paid At</b> <br />' .substr($data->paid_at, 0, 10) . '<br />' .substr($data->paid_at, 11, 10);
            $rows[$x][3] = 'Name : ' . $data->first_name . $data->last_name . '<br/>' .'Phone : ' . $data->mobile_phone . '<br/>' . $data->email  ;    
            $rows[$x][4] = '<b>'.ucwords($data->ticket_name).'</b>' . '<br/>' .$data->product_name . '<br /><b>' .date("D, d/m/Y", strtotime($data->ticket_date)).'</b>'; 
            $rows[$x][5] = $data->qty_ticket . ' / ' .$data->qty_redeemed; 
            $rows[$x][6] = $data->payment_method . '<br/>' .$data->account_name; 
            $rows[$x][7] = 'Rp. ' . number_format($data->total_amount, 0, ',', '.').',-'; 
            
            $x++;
        }
                                
        $result['aaData'] = $rows;
        $result['iTotalRecords'] = $total;
        $result['iTotalDisplayRecords'] = $total;
        return $result;

    }

    public function getDetailDataTable(Request $request){

        $uniqcd = $request->uniqcd;
        $promotion  =   MV_Promo::with(['toTicketImgHd' => function($query1) use ($uniqcd){
                            $query1->where('tmii_ms_ticket_img_hd.type_ticket', 'PROMOTION');
                            $query1->where('tmii_ms_ticket_img_hd.is_active', 'Y');
                            $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
                            $query1->with(['toTicketImgDt' => function($query2){
                                $query2->where('state', 'Y');
                            }]);
                        }])->where('m_promotion_uu', $request->uniqcd)->get();                    

        $data = [
            'promotion' => $promotion
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.master_promotion.datatable-detail-master-promotion', $data) );

        return json_encode(array($modal_content));
    }

    public function storePromotion(Request $request){

        if(!$request->img_type){
            return '';
        }
        $uploadedFile = $request->file('file');        
        $path = $uploadedFile->store('public/img/promo');

        $ticketHD = M_Ticket_Img_HD::where('idem_ticket_uu', $request->idem_ticket_uu);

        if($ticketHD->count()){
            $ticketHDId = $ticketHD->first()->id_ms_ticket_img_hd;
        }else{
            $ticketHDData = M_Ticket_Img_HD::create([
                'cd_ms_ticket_img_hd'   => get_prefix('ms_ticket_img_hd'),
                'idem_ticket_uu'        => $request->idem_ticket_uu,
                'type_ticket'           => 'PROMOTION',
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
    
    public function changeStatePromotion(Request $request){
        if(!$request->cd){
            return 'false';
        }
        M_Ticket_Img_DT::where('cd_ms_ticket_img_dt', $request->cd)
        ->update(['is_active' => DB::raw(
            "CASE 
                WHEN is_active = 'Y' THEN 'N'
                ELSE 'Y'
            END"
        ),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => Auth::guard('administrator')->user()->id_us_backend_hd
        ]);
        return json_encode([M_Ticket_Img_DT::where('cd_ms_ticket_img_dt', $request->cd)->first()->is_active]);
    }
}
