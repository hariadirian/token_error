@extends('layouts.backend-skeleton')

@section('css')

    @include('_partial.css._AdminBSB_form' )
    
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
    <link href="{{ asset('adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendors/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" />
    
@endsection

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>SETUP EVENT CALENDAR</h2>
            </div>
                @if ($errors->has('event_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('event_name') }}</strong>
                    </span>
                @endif
            <!-- #END# Widgets -->
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>EVENT CALENDAR</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
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
                            <div class="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel-body">
                                            @include('_partial.message')
                                            <div id="calendar"></div>
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

<script src="{{ asset('adminbsb/plugins/momentjs/moment.js') }}"></script>
<script src="{{ asset('adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>       
<script src="{{ asset('vendors/fullcalendar/fullcalendar.js') }}"></script>

@include('_partial.js._AdminBSB_table' )
@include('_partial.js._AdminBSB_form' )

<script src="{{ asset('js/pages/_SetupEventCalendar.js') }}"></script>
<script src="{{ asset('adminbsb/js/pages/forms/basic-form-elements.js') }}"></script>
<script src="{{ asset('adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

@endsection