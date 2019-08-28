<?php

namespace App\Http\Controllers\Backend\MasterPromotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Ticket_Img_HD;
use App\Models\M_Ticket_Img_DT;
use Auth;
use DB;

class ApiMasterPromotionController extends Controller
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
            $date_from = date('Y-m-d');
        }

        if($filter['date_to']){
            $myDateTime = \DateTime::createFromFormat('D, d M Y', $filter['date_to']);
            $date_to = $myDateTime->format('Y-m-d');
        }else{
            $date_to = date('Y-m-d');
        }

        // MAIN QUERY
        $datas = MV_Promo::where('isactive', '!=','T');

        if(isset($filter['current'])){
            if($filter['current'] == 'on'){
                $datas->where('isactive', 'Y');
            }else{
                $datas->where('isactive', 'N');
            }
        }else{
            $datas->where('isactive', 'N');
        }
        $datas->where('startdate', '<=', $date_to);
        $datas->where('enddate', '>=', $date_from);

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                $q->orWhere('description',                      'LIKE', '%'.$search.'%');
                $q->orWhereRaw('to_char(startdate, \'YYYY-MM-DD\') LIKE '.'\'%'.$search.'%\'');
                $q->orWhereRaw('to_char(enddate, \'YYYY-MM-DD\') LIKE '.'\'%'.$search.'%\'');
                $q->orWhere('min_val',                          'LIKE', '%'.$search.'%');
            });
        }

        //ORDER BY substr($data->created_at, 11, 8)
        if($request->order[0]['column'] == 0){
            $column = 'm_promotion_id';
        }elseif($request->order[0]['column'] == 1){
            $column = 'm_promotion_id';
        }elseif($request->order[0]['column'] == 2){
            $column = 'description';
        }elseif($request->order[0]['column'] == 3){
            $column = 'startdate';
        }elseif($request->order[0]['column'] == 4){
            $column = 'enddate';
        }elseif($request->order[0]['column'] == 5){
            $column = 'min_val';
        }else{
            $column = 'm_promotion_id';
        }

        $datas->select(DB::raw('ROW_NUMBER() OVER(ORDER BY m_promotion_id asc) AS no'),'m_promotion_id','m_promotion_uu', 'description', 'startdate', 'enddate', 'min_val');

        $datas->orderBy($column, $request->order[0]['dir']);
        
        //FILTER PAGINATION & LIMIT
        $total = $datas->get()->count();
        if($request->length != -1){
            $datas->limit($request->length)->offset($request->start);
        }

        //READY TO PASSING !
        $datas = $datas->get();

        $rows   = array();
        $x      = 0;
       
        foreach($datas as $key => $data){

            $rows[$x][0] = $data->m_promotion_uu;
            $rows[$x][1] = $data->no;
            $rows[$x][2] = $data->description;
            $rows[$x][3] = substr($data->startdate, 0, 10);
            $rows[$x][4] = substr($data->enddate, 0, 10);
            $rows[$x][5] = $data->min_val;     
            
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
