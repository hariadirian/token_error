@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/slick.css')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/slick-theme.css')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/nouislider.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')

	@include('frontend._partial.carousell')

	@include('frontend._partial.summary_tiket')

	@include('frontend._partial.promo_tiket')

	@include('frontend._partial.regular_tiket')

	@include('frontend._partial.reservation')

	{{-- @include('frontend._partial.random_tiket') --}}

@endsection

@section('js')
	<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
	<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
	<script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
	<script src="{{ asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('js/pages/frontend/_Home.js') }}"></script>
@endsection