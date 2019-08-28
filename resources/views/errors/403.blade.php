@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/slick.css')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/slick-theme.css')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/nouislider.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')
<div class="col-sm-12"style="text-align:center">
    <img src="{{ asset('img/forbidden.png') }}" width="70%" />
</div>

@endsection

@section('js')
	<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
	<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
	<script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
	<script src="{{ asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('js/pages/frontend/_Home.js') }}"></script>
@endsection