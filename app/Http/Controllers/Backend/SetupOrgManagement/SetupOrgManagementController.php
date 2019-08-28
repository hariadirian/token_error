<?php

namespace App\Http\Controllers\Backend\SetupOrgManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Models\M_User_Management\M_Us_Backend_Organizations;

class SetupOrgManagementController extends Controller
{
    public function __construct() {
    }

    public function index(){
        return view('backend.setup_org_management.org-management');
    }
    
    public function createOrg(Request $request){

        $this->validate($request, [
            'organization_name' => 'required|string|max:64',
            'organization_type' => 'string|max:64',
            'description' => 'nullable|string|max:128',
            'm_attributeset_uu' => 'required|string|max:128',
        ]);
        $request->merge([
            'cd_us_backend_organization' => get_prefix('us_backend_organization'),
            'created_by' => Auth::guard('administrator')->user()->id_us_backend_hd
        ]);

        M_Us_Backend_Organizations::create($request->all());
        return redirect()->back()->with('success', 'insert');
    }
}
