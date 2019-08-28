@extends('layouts.backend-skeleton')

@section('css')

    @include('_partial.css._AdminBSB_form' )
    
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">

@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Ticket</h2>
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
                                    <h2>SCAN TICKET</h2>
                                </div>
                                <div class="col-xs-12 col-sm-2" style="padding-right:0px">
                                 
                                </div>
                                <div class="col-xs-12 col-sm-2" style="width:14%;padding-right:0px">

                                </div>
                                <div class="col-xs-12 col-sm-1" style="padding-top:5px">
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
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                        @include('_partial.message')
                                        <label id="scanner-label">Scan generated QR Code to get ticket's data.</label>
                                        <canvas id="scanner" class="col-xs-12" style="padding:0;border:1px solid grey"></canvas>
                                        <ul></ul>
                                        <!-- <button id="qwe">Click me</button> -->
                                        <div id="scan-result"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-scanner" >
                                        <div class="col-md-5 ">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="form-line">
                                                        <input id="input-qrcode" type="text" class="form-control" placeholder="Input Manual QR Code">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <button id="btn-qrcode" type="button" class="btn btn-primary btn-md m-l-15 waves-effect">
                                                        <i class="material-icons">send</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button title="Stop streams" class="btn btn-danger btn-sm pull-right" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop" style="padding-top:8px;padding-right:5px"></span> <span style="top:-4px">Stop Scanner</span></button>
                                        <button title="Play" class="btn btn-success btn-sm pull-right" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play" style="padding-top:8px;padding-right:5px"></span> <span style="top:-4px">Run Scanner</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')

    <!-- <script src="{{ asset('adminbsb/plugins/jquery-validation/jquery.validate.js') }}"></script>        -->
    <script src="{{ asset('adminbsb/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/momentjs/moment.js') }}"></script>

    @include('_partial.js._AdminBSB_form' )
    
    <script src="{{ asset('adminbsb/js/pages/forms/basic-form-elements.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('js/qrcodelib.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/webcodecamjs.js') }}"></script>
    <script src="{{ asset('js/pages/_TicketScan.js') }} "></script>
    
@endsection