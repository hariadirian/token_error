<?php

namespace App\Http\Controllers\Backend\MasterProduct;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M_Idempiere\M_View\MV_Product;
use Auth;

class MasterProductController extends Controller
{
    public function __construct() {}

    public function index(){
        return view('backend.master_product.master-product');
    }
}
