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
                <h2>Sales Report</h2>
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
                                <div class="col-xs-12 col-sm-2">
                                    {!! Form::select('State', ['State Filter' => ['on_progress' => 'On Progress', 'paid' => 'Paid', 'visited' => 'Visited', 'cancelled' => 'Cancelled', 'expired' => 'Expired']], null, ['class' => 'additional-filter form-control show-tick', 'name' => 'state_filter', 'id' => 'state_option', 'data-live-search' => 'true', 'placeholder' => 'Select State']) !!}
                                </div>
                                <div class="col-xs-12 col-sm-2 align-right">
                                    {!! Form::select('Wahana',  ['Wahana' =>  $organizations->toArray()], null, ['class' => 'additional-filter form-control show-tick', 'name' => 'wahana_filter', 'id' => 'wahana_option', 'data-live-search' => 'true', 'placeholder' => 'Select Wahana']) !!}
                                </div>
                                <div class="col-xs-12 col-sm-2 align-right">
                                    {!! Form::select('Cust', ['Calendar Filter' => ['weekday' => 'Weekday','weekend' => 'Weekend', 'event' => 'Event']], null, ['class' => 'additional-filter form-control show-tick', 'name' => 'calendar_filter', 'id' => 'calendar_option', 'data-live-search' => 'true', 'placeholder' => 'Select Calendar']) !!}
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    
                                    {!! Form::text('cd_order', null, ['id' => 'cd_order', 'class' => 'additional-filter form-control', 'maxlength' => 32, 'placeholder' => 'Code Order' ]) !!}
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
                                            <table selected-row="0" id="main-table" class="table table-hover js-exportable-ajax dataTable" data-source-tb="ReportSales" width="100%" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style=""></th>
                                                        <th style="width:10px">No</th>
                                                        <th>Created</th>
                                                        <th>Customer</th>
                                                        <th>Ticket</th>
                                                        <th>Qty</th>
                                                        <th>Payment</th>
                                                        <th width="80px">Total</th>
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

    <script src="{{ asset('adminbsb/plugins/jquery-validation/jquery.validate.js') }}"></script>       
    <script src="{{ asset('adminbsb/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/momentjs/moment.js') }}"></script>

    @include('_partial.js._AdminBSB_table' )
    @include('_partial.js._AdminBSB_form' )

    <script src="{{ asset('js/pages/_MasterPromotion.js') }}"></script>
    <script src="{{ asset('adminbsb/js/pages/forms/basic-form-elements.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
    
@endsection