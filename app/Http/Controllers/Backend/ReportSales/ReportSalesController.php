<?php

namespace App\Http\Controllers\Backend\ReportSales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_User_Management\M_Us_Backend_Organizations;
use Auth;

class ReportSalesController extends Controller
{

    public function __construct() {}

    public function index(){
        $organizations  = M_Us_Backend_Organizations::IsActive('Y')
                            ->pluck('organization_name', 'cd_us_backend_organization');
        return view('backend.report_sales.report-sales', compact('organizations'));
    }

    public function templateImage($type){
        $file= public_path(). "/adminbsb/images/template/".$type.".png";
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $type.".png", $headers);
    }
}
