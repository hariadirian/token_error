<?php

namespace App\Http\Controllers\Backend\ReportCustomer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ReportCustomerController extends Controller
{
    public function __construct() {}

    public function index(){
        return view('backend.report.customer.report-customer');
    }
}
