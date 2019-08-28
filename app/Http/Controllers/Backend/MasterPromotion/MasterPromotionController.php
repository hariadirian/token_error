<?php

namespace App\Http\Controllers\Backend\MasterPromotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Promo;
use Auth;

class MasterPromotionController extends Controller
{

    public function __construct() {}

    public function index(){
        return view('backend.master_promotion.master-promotion');
    }

    public function templateImage($type){
        $file= public_path(). "/adminbsb/images/template/".$type.".png";
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $type.".png", $headers);
    }
}
