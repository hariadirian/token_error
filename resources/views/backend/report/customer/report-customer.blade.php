@extends('layouts.backend-skeleton')

@section('css')

    @include('_partial.css._AdminBSB_table' )
    @include('_partial.css._AdminBSB_form' )
    
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
    <link href="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbsb/plugins/dropzone/dropzone.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />

@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>REPORT DAFTAR CUSTOMER</h2>
            </div>
            @if ($errors->has('event_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('event_name') }}</strong>
                </span>
            @endif
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div id="main-table-search" class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>CUSTOMER</h2>
                                </div>
                                <div class="col-xs-12 col-sm-2" style="padding-right:0px"></div>
                                <div class="col-xs-12 col-sm-2" style="width:14%;padding-right:0px"></div>
                                <div class="col-xs-12 col-sm-1" style="padding-top:5px">
                                    <input type="checkbox" id="current" name="current" class="filled-in additional-filter" checked />
                                    <label for="current"><b>ACTIVE</b></label>
                                </div>
                                <div class="col-xs-12 col-sm-1"></div>
                                <div class="col-xs-12 col-sm-3 align-right"style="padding-top:5px">

                                </div>
                            </div>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href=href="#modalForm" data-toggle="modal" data-target="#newCustomerModal">Tambah Customer Baru</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-body">
                                        @include('_partial.message')
                                        <div class="table-responsive" style="overflow-x: visible; ">
                                            <table selected-row="1" id="main-table" class="table table-hover js-exportable-ajax dataTable" data-source-tb="ReportCustomer"  width="100%" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style=""></th>
                                                        <th style="width:10px">No</th>
                                                        <th width="150px">Nama</th>
                                                        <th width="10px">Email</th>
                                                        <th width="20px">Nomor HP</th>
                                                        <th width="100px">Alamat</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div id="newCustomerModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353">Tambah Customer Baru</h3>
                        <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                            <hr style="border-top:1px solid #babeff"></hr>
                        </div>
                    </div>
                    <div class="modal-body" style="overflow: hidden;">
                        <div class="input-group">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line"> 
                                        <input type="text" id="first_name" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Nama Depan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" id="last_name" class="form-control form-bright"> 
                                        <label class="form-label" style="color:#1e5f98 !important">Nama Belakang</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-12">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" id="address" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Alamat</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" id="email" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" id="mobile_phone" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Nomor HP</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" id="password" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" id="password_confirmation" class="form-control form-bright">
                                        <label class="form-label" style="color:#1e5f98 !important">Ketik Ulang Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            Submit
                        </button>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/jquery-validation/jquery.validate.js') }}"></script>       
    <script src="{{ asset('adminbsb/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/momentjs/moment.js') }}"></script>

    @include('_partial.js._AdminBSB_table' )
    @include('_partial.js._AdminBSB_form' )

    <script src="{{ asset('js/pages/_ReportCustomer.js') }}"></script>
    <script src="{{ asset('adminbsb/js/pages/forms/basic-form-elements.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
@endsection