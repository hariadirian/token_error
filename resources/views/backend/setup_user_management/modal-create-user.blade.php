
<div class="modal-header" style="padding-bottom:25px">
    <h5 class="modal-title" style="font-size:25px" id="largeModalLabel">Form Create User</h5>
</div>

{!! Form::open(['route' => 'store.user.create.save', 'id' => 'fom']) !!}
    <div class="modal-body" >
        <div class="row" style="padding:50px 10px 0px 10px;margin:0px 10px;border:0.5px solid aliceblue">
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('username')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('username', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32 ]) !!}
                            {!! Form::label('username', 'Username', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('password')) focused error @endif">
                        <div class="form-line">
                            {!! Form::password('password', ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32, 'minlength' => 6 ]) !!}
                            {!! Form::label('password', 'Password', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('password')) focused error @endif">
                        <div class="form-line">
                            {!! Form::password('password_confirmation', ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32, 'minlength' => 6 ]) !!}
                            {!! Form::label('password_confirmation', 'Re-enter password', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group input-group-indigo" style="z-index:3;display:table-caption">
                    {!! Form::select('roles', ['Role Management' =>  $roles->toArray()], null, ['class' => 'form-control show-tick form-detail-edit', 'multiple' => 'multiple', 'name' => 'roles[]', 'id' => 'roles_option', 'data-live-search' => 'true']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group input-group-indigo" style="display:table-caption">
                    {!! Form::select('organizations', ['Organization Management' =>  $organizations->toArray()], null, ['class' => 'form-control show-tick', 'multiple' => 'multiple', 'name' => 'organizations[]', 'id' => 'roles_option']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('nip')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('nip', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32 ]) !!}
                            {!! Form::label('nip', 'NIP', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('first_name')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('first_name', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 64 ]) !!}
                            {!! Form::label('first_name', 'First Name', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('last_name')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('last_name', null, ['class' => 'form-control form-indigo', 'maxlength' => 64 ]) !!}
                            {!! Form::label('last_name', 'Last Name', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('id_card')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('id_card', null, ['class' => 'form-control form-indigo', 'maxlength' => 64 ]) !!}
                            {!! Form::label('id_card', 'ID Card / KTP', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('mobile_phone')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('mobile_phone', null, ['class' => 'form-control form-indigo', 'maxlength' => 64 ]) !!}
                            {!! Form::label('mobile_phone', 'Mobile Phone', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('address')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('address', null, ['class' => 'form-control form-indigo', 'maxlength' => 128 ]) !!}
                            {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" id="save" class="btn btn-link waves-effect save">SAVE CHANGES</button>
        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
    </div>
{!! Form::close() !!}
