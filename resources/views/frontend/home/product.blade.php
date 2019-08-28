@extends('layouts.frontend-skeleton')

@section('css')

    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')
	<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ URL::asset('/') }}">Home</a></li>
				<li class="active">Tiket Reguler</li>
			</ul>
		</div>
	</div>
	@include('frontend._partial.regular_tiket')

@endsection

@section('js')

@endsection