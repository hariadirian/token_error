<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        <div class="panel-body bg-bright">
            @foreach($customers as $customer)
                <h3 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353">Detail Customer</h3>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    {!! Form::hidden('id_us_frontend_hd', $customer->id_us_frontend_hd, ['id' => 'id_us_frontend_hd']) !!}
                    <div class="input-group">
                        <div class="col-md-5">
                            <div class="form-group form-float">
                                <div class="form-line"> 
                                    <input type="text" class="form-control form-bright" value="{{ $customer->toUsFrontendDt()->first()->first_name }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Nama Depan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->toUsFrontendDt()->first()->last_name }}"> 
                                    <label class="form-label" style="color:#1e5f98 !important">Nama Belakang</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ substr($customer->is_active, 0, 10) }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->email }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->toUsFrontendDt()->first()->mobile_phone }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Nomor HP</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->created_at }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Tanggal Registrasi</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->actived_at }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Tanggal Aktivasi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-8">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->toUsFrontendDt()->first()->address }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Alamat</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->toUsFrontendDt()->first()->institute }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Institusi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->google_id }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Google</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->facebook_id }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Facebook</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $customer->twitter_id }}">
                                    <label class="form-label" style="color:#1e5f98 !important">Twitter</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    $.AdminBSB.input.activate();
</script>