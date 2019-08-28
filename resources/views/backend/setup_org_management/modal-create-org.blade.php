
<div class="modal-header" style="padding-bottom:25px">
    <h5 class="modal-title" style="font-size:25px" id="largeModalLabel">Form Create Organization</h5>
</div>

{!! Form::open(['route' => 'store.org.create.save', 'id' => 'fom']) !!}
    <div class="modal-body" >
        <div class="row" style="padding:50px 10px 0px 10px;margin:0px 10px;border:0.5px solid aliceblue">
            <div class="col-sm-12">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('organization_name')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('organization_name', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32 ]) !!}
                            {!! Form::label('organization_name', 'Organization Name', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('organization_type')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('organization_type', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 32 ]) !!}
                            {!! Form::label('organization_type', 'Organization Type', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <div class="form-group form-float  @if ($errors->has('description')) focused error @endif">
                        <div class="form-line">
                            {!! Form::text('description', null, ['class' => 'form-control form-indigo', 'maxlength' => 64 ]) !!}
                            {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="input-group input-group-indigo" style="z-index:3;display:table-caption">
                    {!! Form::select('m_attributeset_uu', ['Product Attribute Set' =>  $attributeset->toArray()], null, ['class' => 'form-control show-tick form-detail-edit',  'name' => 'm_attributeset_uu', 'id' => 'attributeset_option', 'data-live-search' => 'true', 'placeholder' => 'Please select ...']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" id="save" class="btn btn-link waves-effect save">SAVE CHANGES</button>
        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
    </div>
{!! Form::close() !!}
