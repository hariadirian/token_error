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

class ApiSetupUserManagementController extends Controller
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
            $date_from = '2019-01-01';
        }

        if($filter['date_to']){
            $myDateTime = \DateTime::createFromFormat('D, d M Y', $filter['date_to']);
            $date_to = $myDateTime->format('Y-m-d H:i:s');
        }else{
            $date_to = date('Y-m-d H:i:s');
        }

        // MAIN QUERY
        $datas = M_Us_Backend_HD::leftJoin('us_backend_dt as b', function($join){
                $join->on('us_backend_hd.id_us_backend_hd', '=', 'b.id_us_backend_hd');
                $join->where('b.state', '=', 'Y');
                $join->where('b.is_active', '=', 'Y');
            })
            ->leftJoin('us_backend_role_user as c', 
                'us_backend_hd.id_us_backend_hd', '=', 'c.user_id')
            ->leftJoin('us_backend_roles as d', 
                'c.role_id', '=', 'd.id')
            ->where('us_backend_hd.state', 'Y');
        
        if(isset($filter['current'])){
            if($filter['current'] == 'on'){
                $datas->where('us_backend_hd.is_active', 'Y');
            }else{
                $datas->where('us_backend_hd.is_active', 'N');
            }
        }else{
            $datas->where('us_backend_hd.is_active', 'N');
        }
        $datas->where('us_backend_hd.created_at', '<=', $date_to);
        $datas->where('us_backend_hd.created_at', '>=', $date_from);

        // FILTER WHERE
        if($request->search['value'] != ''){
            $search = strtoupper($request->search['value']);
            $datas->where(function($q) use($datas, $search){
                $q->orWhere('username', 'LIKE', '%'.$search.'%');
                $q->orWhere('nip', 'LIKE', '%'.$search.'%');
                $q->orWhere('first_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('last_name', 'LIKE', '%'.$search.'%');
                $q->orWhere('us_backend_hd.created_at', 'LIKE', '%'.$search.'%');
                $q->orWhere('d.display_name', 'LIKE', '%'.$search.'%');
            });
        }

        //ORDER BY substr($data->created_at, 11, 8)
        if($request->order[0]['column'] == 0){
            $column = 'us_backend_hd.id_us_backend_hd';
        }elseif($request->order[0]['column'] == 1){
            $column = 'us_backend_hd.id_us_backend_hd';
        }elseif($request->order[0]['column'] == 2){
            $column = 'username';
        }elseif($request->order[0]['column'] == 3){
            $column = 'full_name';
        }elseif($request->order[0]['column'] == 5){
            $column = 'us_backend_hd.created_at';
        }elseif($request->order[0]['column'] == 6){
            $column = 'us_backend_hd.is_active';
        }else{
            $column = 'us_backend_hd.id_us_backend_hd';
        }

        $datas->select(DB::raw('@row:=@row+1 AS no'), DB::raw("min(us_backend_hd.cd_us_backend_hd) as cd_us_backend_hd"), DB::raw("min(us_backend_hd.username) as username"), DB::raw("concat(min(first_name), ' ', min(last_name)) as full_name"), DB::raw("GROUP_CONCAT(d.display_name ORDER BY d.display_name ASC SEPARATOR ', ') as roles"), DB::raw("min(us_backend_hd.created_at) as created_at"), DB::raw("min(us_backend_hd.is_active) as is_active"));

        $datas->groupBy('us_backend_hd.id_us_backend_hd');
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
        if($datas){
            foreach($datas as $key => $data){

                $rows[$x][0] = $data->cd_us_backend_hd;
                $rows[$x][1] = $data->no;
                $rows[$x][2] = $data->username;
                $rows[$x][3] = $data->full_name;
                $rows[$x][4] = $data->roles;
                $rows[$x][5] = date('Y-m-d H:i:s', strtotime($data->created_at)); 
                $rows[$x][6] = ($data->is_active == 'Y')? 'Active' : 'Not Active'; 
                
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
        $user_hd  = M_Us_Backend_HD::with(['toUsBackendDt' => function($query1){
            $query1->where('us_backend_dt.state', 'Y');
        }])->where('state', 'Y')->where('cd_us_backend_hd', $uniqcd)->get(); 
        
        $roles          = M_Us_Role::IsActive('Y')
                            ->pluck('display_name', 'code_roles');
        $organizations  = M_Us_Backend_Organizations::IsActive('Y')
                            ->pluck('organization_name', 'cd_us_backend_organization');

        $data = [
            'user_hd'       => $user_hd,
            'roles'         => $roles,
            'organizations' => $organizations
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.setup_user_management.datatable-detail-user-management', $data) );
        return json_encode(array($modal_content));
    }

    public function createUserModal(){
        
        $roles          = M_Us_Role::IsActive('Y')
                            ->pluck('display_name', 'code_roles');
        $organizations  = M_Us_Backend_Organizations::IsActive('Y')
                            ->pluck('organization_name', 'cd_us_backend_organization');

        $datas = [
            'roles'         => $roles,
            'organizations' => $organizations
        ];

        $modal_content = preg_replace( "/\r|\n/", "", view('backend.setup_user_management.modal-create-user', $datas) );
        return json_encode(['content' => $modal_content]);
    }

    public function updateUser(Request $request){

        foreach($request->datas as $key => $data){
            eval('return $'. $data['name']. ' = \''.$data['value'].'\';');
        }
        $user = M_Us_Backend_Hd::where('cd_us_backend_hd', $cd_us_backend_hd)->first();
        if(!$user->count())
            return ['state' => 0, 'message' => 'User is not found!'];

        $log_roles = '';
        $org_roles = '';
        foreach($user->roles as $old_roles){
            $log_roles = $log_roles? $log_roles.', ' : ''; 
            $log_roles .= $old_roles->id; 
        }
        if($user->log_roles){
           $log_roles = $user->log_roles.';'.$log_roles; 
        }
        foreach($user->toUsBackendOrganizationUser->where('is_active', 'Y')->where('state', 'Y') as $old_org){
            $org_roles = $org_roles? $org_roles.', ' : ''; 
            $org_roles .= $old_org->id_us_backend_organization; 
        }
        $log_roles .= ':'.$org_roles.':'.date('YmdHis').'-'.Auth::guard('administrator')->user()->id_us_backend_hd;
        
        DB::beginTransaction();
        try {
            $user->update([
                'username' => $username,
                'log_roles' => $log_roles,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::guard('administrator')->user()->id_us_backend_hd
            ]);
            $user->toUsBackendDt->where('state', 'Y')->where('is_active','Y')->where('cd_us_backend_dt', $cd_us_backend_dt)->update([
                'nip' => $nip,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'id_card' => $id_card,
                'mobile_phone' => $mobile_phone,
                'address' => $address,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::guard('administrator')->user()->id_us_backend_hd
            ]);
            
            $user->detachRoles($user->roles);
            $roles['id'] = M_Us_Role::whereIn('code_roles', $roles)->pluck('id')->toArray();
            $user->attachRole($roles);

            $user->toUsBackendOrganizationUser()->update([
                'is_active' => 'N',
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => Auth::guard('administrator')->user()->id_us_backend_hd
            ]);
            foreach(M_Us_Backend_Organizations::whereIn('cd_us_backend_organization', $organizations)->get() as $new_org){
                M_Us_Backend_Organization_User::create([
                    'id_us_backend_hd'           => $user->id_us_backend_hd,
                    'id_us_backend_organization' => $new_org->id_us_backend_organization,
                    'created_by'                 => Auth::guard('administrator')->user()->id_us_backend_hd
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode(['state' => 0, 'message' => 'Save data failed! Check your data before saving it.']);
        }
        return json_encode(['state' => 1, 'message' => 'Your data has been successfully saved!']);
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
        )]);
        return json_encode([M_Ticket_Img_DT::where('cd_ms_ticket_img_dt', $request->cd)->first()->is_active]);
    }
}
