<?php

namespace App\Http\Controllers\Backend\MasterProduct;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Product;
use App\Models\M_Ticket_Img_HD;
use App\Models\M_Ticket_Img_DT;
use Auth;
use DB;

class ApiMasterProductController extends Controller
{

    public function getDataTable(Request $request){

        if ($request->additional_filter) {
            foreach ($request->additional_filter as $par) {
                $filter[$par['name']] = $par['value'];
            }
        }

        // MAIN QUERY
        $datas = MV_Product::where('isactive', '!=','T');

        if(isset($filter['current'])){
            if($filter['current'] == 'on'){
                $datas->where('isactive', 'Y');
            }else{
                $datas->where('isactive', 'N');
            }
        }else{
            $datas->where('isactive', 'N');
        }

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                $q->orWhere('name',                      'LIKE', '%'.$search.'%');
                $q->orWhereRaw('to_char(created, \'YYYY-MM-DD HH:ii:ss\') LIKE '.'\'%'.$search.'%\'');
                $q->orWhereRaw('CAST(pekan_value AS TEXT) LIKE '.'\'%'.$search.'%\'');
                $q->orWhereRaw('CAST(holiday_value AS TEXT) LIKE '.'\'%'.$search.'%\'');
                $q->orWhereRaw('CAST(weekdays_value AS TEXT) LIKE '.'\'%'.$search.'%\'');
            });
        }

        //ORDER BY substr($data->created_at, 11, 8)
        if($request->order[0]['column'] == 0){
            $column = 'm_product_id';
        }elseif($request->order[0]['column'] == 1){
            $column = 'm_product_id';
        }elseif($request->order[0]['column'] == 2){
            $column = 'name';
        }elseif($request->order[0]['column'] == 3){
            $column = 'weekdays_value';
        }elseif($request->order[0]['column'] == 4){
            $column = 'holiday_value';
        }elseif($request->order[0]['column'] == 5){
            $column = 'pekan_value';
        }elseif($request->order[0]['column'] == 6){
            $column = 'created';
        }else{
            $column = 'm_product_id';
        }

        $datas->select(DB::raw('ROW_NUMBER() OVER(ORDER BY m_product_id asc) AS no'), 'm_product_id','m_product_uu', 'name', 'pekan_value', 'holiday_value', 'weekdays_value', 'created');

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
       
       if($datas){
            foreach($datas as $key => $data){

                $rows[$x][0] = $data->m_product_uu;
                $rows[$x][1] = $data->no;
                $rows[$x][2] = $data->name;
                $rows[$x][3] = number_format($data->weekdays_value,0,',','.');
                $rows[$x][4] = number_format($data->holiday_value,0,',','.');
                $rows[$x][5] = number_format($data->pekan_value,0,',','.');     
                $rows[$x][6] = substr($data->created, 0, 10);
                
                $x++;
            }
       }
                                
        $result['aaData'] = $rows;
        $result['iTotalRecords'] = $total;
        $result['iTotalDisplayRecords'] = $total;
        return $result;

    }

    public function getDetailDataTable(Request $request){

        $uniqcd = $request->uniqcd;
        $product  =   MV_Product::with(['toTicketImgHd' => function($query1) use ($uniqcd){
                            $query1->where('tmii_ms_ticket_img_hd.type_ticket', 'PRODUCT');
                            $query1->where('tmii_ms_ticket_img_hd.is_active', 'Y');
                            $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
                            $query1->with(['toTicketImgDt' => function($query2){
                                $query2->where('state', 'Y');
                            }]);
                        }])->where('m_product_uu', $uniqcd)->get();                    

        $data = [
            'products' => $product
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.master_product.datatable-detail-master-product', $data) );

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
}
