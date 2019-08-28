<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        {!! Form::open(['route' => 'store.user.update', 'id' => 'form-detail']) !!}
        <div class="panel-body bg-bright">
            @foreach($user_hd as $user)
            
                <!-- --------------------- ACCOUNT DETAIL ------------------------------>
                <h4 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353" class="pull-left">Account Detail</h4>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    {!! Form::hidden('cd_us_backend_hd', $user->cd_us_backend_hd, ['id' => 'cd_us_backend_hd', 'disabled' => 'disabled', 'class' => 'form-detail-edit']) !!}
                    <div class="input-group" style="margin-bottom:0">
                        <div class="col-md-8">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('username', $user->username, ['class' => 'form-control form-detail-edit form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('username', 'Username', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('actived_at', ($user->actived_at? $user->actived_at : '-'), ['class' => 'form-control form-bright', 'disabled' => 'disabled']) !!}
                                    {!! Form::label('actived_at', 'Actived At', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('is_active', ($user->is_active == 'Y'? 'Active' : 'Not Active'), ['class' => 'form-control form-bright', 'disabled' => 'disabled']) !!}
                                    {!! Form::label('is_active', 'Active State', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group" >
                        <div class="col-md-6">
                            {!!  Form::label('roles', 'Roles', ['class' => 'form-label form-bright-label'])  !!}
                            <div class="form-group">
                                {!! Form::select('roles', [
                                    'Role Management' =>  $roles->toArray(),
                                ], $user->roles->pluck('code_roles')->toArray()
                                , ['class'=> 'form-detail-edit', 'multiple' => 'multiple', 'name' => 'roles[]', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            {!!  Form::label('organizations', 'Organizations', ['class' => 'form-label form-bright-label'])  !!}
                            <div class="form-group">
                                {!! Form::select('organizations', [
                                    'Organization Management' => $organizations->toArray()
                                ], $user->toUsBackendOrganizationUser->where('is_active', 'Y')->pluck('toUsBackendOrganizations.cd_us_backend_organization')->toArray(), ['class'=> 'form-detail-edit', 'multiple' => 'multiple', 'name' => 'organizations[]', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="input-group" style="margin-bottom:0">
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('created_at', isset($user->created_at)? $user->created_at : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('created_at', 'Created At', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('created_by', isset($user->toCreatedBy->username)? $user->toCreatedBy->username : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('created_by', 'Created By', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('updated_at', isset($user->updated_at)? $user->updated_at : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('updated_at', 'Updated At', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('updated_by', isset($user->toUpdatedBy->username)? $user->toUpdatedBy->username : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('updated_by', 'Updated By', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('deleted_at', isset($user->deleted_at)? $user->deleted_at : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('deleted_at', 'Deleted At', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('deleted_by', isset($user->toDeletedBy->username)? $user->toDeletedBy->username : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('deleted_by', 'Deleted By', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- --------------------- USER DETAIL ------------------------------>
                <div class="col-sm-12" style="margin:0;padding:0">
                    <h4 style="margin-left:20px;margin-bottom:0px;color:#535353" class="pull-left">Customer Detail</h4>
                </div>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    {!! Form::hidden('cd_us_backend_dt', $user->toUsBackendDt->cd_us_backend_dt, ['id' => 'cd_us_backend_dt', 'disabled' => 'disabled', 'class' => 'form-detail-edit']) !!}
                    <div class="input-group">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('nip', $user->toUsBackendDt->nip, ['class' => 'form-detail-edit form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 16 ]) !!}
                                    {!! Form::label('nip', 'NIP', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('first_name', $user->toUsBackendDt->first_name, ['class' => 'form-detail-edit form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('first_name', 'First Name', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('last_name', $user->toUsBackendDt->last_name, ['class' => 'form-detail-edit form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('last_name', 'Last Name', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('id_card', $user->toUsBackendDt->id_card, ['class' => 'form-detail-edit form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 64 ]) !!}
                                    {!! Form::label('id_card', 'ID Card', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('mobile_phone', $user->toUsBackendDt->mobile_phone, ['class' => 'form-detail-edit form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 16 ]) !!}
                                    {!! Form::label('mobile_phone', 'Mobile Phone', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::textarea('address', $user->toUsBackendDt->address, ['class' => 'form-detail-edit form-control form-bright auto-growth', 'disabled' => 'disabled', 'maxlength' => 128, 'style' => ';overflow: hidden; overflow-wrap: break-word; height: 32px;']) !!}
                                    {!! Form::label('address', 'Address', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('updated_at', isset($user->toUsBackendDt->updated_at)? $user->toUsBackendDt->updated_at : '-', ['class' => 'form-control form-bright', 'disabled' => 'disabled', 'maxlength' => 128 ]) !!}
                                    {!! Form::label('updated_at', 'Updated At', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {!! Form::text('updated_by', isset($user->toUsBackendDt->toUpdatedBy->username)? $user->toUsBackendDt->toUpdatedBy->username : '-', ['class' => 'form-control form-bright', 'readonly' => 'readonly', 'maxlength' => 64 ]) !!}
                                    {!! Form::label('updated_by', 'Updated By', ['class' => 'form-label form-bright-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-bottom:20px">
                        <button type="button" class="btn btn-warning edit-form-detail" style="width:200px">Edit Data</button>
                        <button type="button" class="btn btn-success save-form-detail" style="width:200px;display:none">Save Data</button>
                    </div>
                </div>
            @endforeach
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $.AdminBSB.input.activate();
    $.AdminBSB.dropdownMenu.activate();
    $.AdminBSB.select.activate();
</script>