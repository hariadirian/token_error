<?php

namespace App\Http\Controllers\Backend\ReportCustomer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_User_Management\M_Us_Frontend_HD;
use App\Models\M_User_Management\M_Us_Frontend_DT;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Response;

class ApiReportCustomerController extends Controller
{

    public function getDataTable(Request $request){

        if ($request->additional_filter) {
            foreach ($request->additional_filter as $par) {
                $filter[$par['name']] = $par['value'];
            }
        }

        // MAIN QUERY
        $datas = M_Us_Frontend_HD::query();

        if(isset($filter['current'])){
            if($filter['current'] == 'on'){
                $datas->where('is_active', 'Y');
            }else{
                $datas->where('is_active', 'N');
            }
        }else{
            $datas->where('is_active', 'N');
        }

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                $q->orWhere('email','LIKE', '%'.$search.'%');
            });
        }
        
        //$datas->select(DB::raw('ROW_NUMBER() OVER(ORDER BY id_us_frontend_hd asc) AS no'), 'id_us_frontend_hd', 'email');

        //FILTER PAGINATION & LIMIT
        $total = $datas->get()->count();
        if($request->length != -1){
            $datas->limit($request->length)->offset($request->start);
        }

        //READY TO PASSING !
        $datas = $datas->get();

        $rows   = array();
        $x      = 0;
        $y      = 1;
       
       if($datas){
            foreach($datas as $key => $data){

                $rows[$x][0] = $data->id_us_frontend_hd;
                $rows[$x][1] = $y;
                $rows[$x][2] = $data->toUsFrontendDt()->first()->first_name.' '.$data->toUsFrontendDt()->first()->last_name;
                $rows[$x][3] = $data->email;
                $rows[$x][4] = $data->toUsFrontendDt()->first()->mobile_phone;
                $rows[$x][5] = $data->toUsFrontendDt()->first()->address;
                
                $x++;
                $y++;
            }
       }
                                
        $result['aaData'] = $rows;
        $result['iTotalRecords'] = $total;
        $result['iTotalDisplayRecords'] = $total;
        return $result;

    }

    public function getDetailDataTable(Request $request){

        $customer_id = $request->uniqcd;
        $customer    = M_Us_Frontend_HD::where('id_us_frontend_hd', $customer_id)->get();                    

        $data = [
            'customers' => $customer
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.report.customer.datatable-detail-report-customer', $data) );

        return json_encode(array($modal_content));
    }
}
