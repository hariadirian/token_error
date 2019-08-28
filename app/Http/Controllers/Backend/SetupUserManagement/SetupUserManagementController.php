<?php

namespace App\Http\Controllers\Backend\SetupUserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Hash;
use App\Models\M_User_Management\M_Us_Backend_HD;
use App\Models\M_User_Management\M_Us_Backend_DT;
use App\Models\M_User_Management\M_Us_Role;
use App\Models\M_User_Management\M_Us_Backend_Organizations;
use App\Models\M_User_Management\M_Us_Backend_Organization_User;

class SetupUserManagementController extends Controller
{
    public function __construct() {
    }

    public function index(){
        return view('backend.setup_user_management.user-management');
    }
    
    public function createUser(Request $request){

        $this->validate($request, [
            'username' => 'required|string|max:32|unique:us_backend_hd',
            'password' => 'required|confirmed|max:32|min:6',
            'roles' => 'required',
            'nip' => 'required|string|max:32',
            'first_name' => 'required|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'id_card' => 'nullable|string|max:64',
            'mobile_phone' => 'nullable|string|max:64',
            'address' => 'nullable|string|max:128'
        ]);
        $created_by = Auth::guard('administrator')->user()->id_us_backend_hd;

        DB::beginTransaction();
        try {

            $us_backend_hd = M_Us_Backend_HD::create([
                'cd_us_backend_hd' => get_prefix('us_backend_hd'),
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'registered_token' => 'CREATEDBYMANUAL',
                'actived_at ' => date('Y-m-d H:i:s'),
                'is_active ' => 'Y',
                'created_by ' => $created_by,
            ]);
            
            $us_backend_dt = M_Us_Backend_DT::create([
                'cd_us_backend_dt' => get_prefix('us_backend_dt'),
                'id_us_backend_hd' => $us_backend_hd->id_us_backend_hd,
                'nip' => $request->nip,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'id_card' => $request->id_card,
                'mobile_phone' => $request->mobile_phone,
                'address' => $request->address,
                'created_by' => $created_by,
            ]);
            
            $roles['id'] = M_Us_Role::whereIn('code_roles', $request->roles)->pluck('id')->toArray();
            $us_backend_hd->attachRole($roles);
            
            foreach(M_Us_Backend_Organizations::whereIn('cd_us_backend_organization', $request->organizations)->get() as $new_org){
                M_Us_Backend_Organization_User::create([
                    'id_us_backend_hd'           => $us_backend_hd->id_us_backend_hd,
                    'id_us_backend_organization' => $new_org->id_us_backend_organization,
                    'created_by'                 => Auth::guard('administrator')->user()->id_us_backend_hd
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failed', '');
        }

        return redirect()->back()->with('success', 'insert');
    }
}
