@extends('layouts.backend-skeleton')

@section('css')

    @include('_partial.css._AdminBSB_table' )
    @include('_partial.css._AdminBSB_form' )
    
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
    <link href="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
    <!-- <link href="{{ asset('adminbsb/plugins/dropzone/dropzone.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" /> -->
    
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
                                <div class="col-xs-12 col-sm-3">
                                    <h2>GENERATED TICKET</h2>
                                </div>
                                <div class="col-xs-12 col-sm-3"><a class="btn" href="{{ URL::asset('ticket/temporary_process_paid_all_cart_order') }}">Paid All Cart order</a></div>
                                <div class="col-xs-12 col-sm-2" style="padding-right:0px">
                                    <div class="input-group" style="margin-bottom:0px">
                                        <div class="form-line">
                                            <input type="text" id="date_from" name="date_from" class="datepicker form-control additional-filter" placeholder="Date from" >
                                        </div>
                                        <span class="input-group-addon">
                                            <i class="material-icons">date_range</i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2" style="width:14%;padding-right:0px">
                                    <div class="input-group" style="margin-bottom:0px">
                                        <div class="form-line">
                                            <input type="text" id="date_to" name="date_to" class="datepicker form-control additional-filter" placeholder="Date to" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-1" style="padding-top:5px">
                                    <input type="checkbox" id="current" name="current" class="filled-in additional-filter" checked />
                                    <label for="current"><b>ACTIVE</b></label>
                                    <button type="hidden" class="btn" id="refresh"></button>
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
                                    <div class="panel-body">
                                        @include('_partial.message')
                                        <div class="table-responsive" style="overflow-x: visible; ">
                                            <table selected-row="1" id="main-table" class="table table-hover js-exportable-ajax dataTable" data-source-tb="Ticket" width="100%" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style=""></th>
                                                        <th width="10px">No</th>
                                                        <th width="120px">Order Code</th>
                                                        <th width="80px">Email</th>
                                                        <th width="150px">Total Product</th>
                                                        <th width="150px">Total Price</th>
                                                        <th width="150px">Generated At</th>
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
    </section>
@endsection

@section('js')

    <!-- <script src="{{ asset('adminbsb/plugins/jquery-validation/jquery.validate.js') }}"></script>        -->
    <script src="{{ asset('adminbsb/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/momentjs/moment.js') }}"></script>

    @include('_partial.js._AdminBSB_table' )
    @include('_partial.js._AdminBSB_form' )

    <script src="{{ asset('adminbsb/js/pages/forms/basic-form-elements.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
    
@endsection