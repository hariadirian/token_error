@extends('layouts.frontend-skeleton')
@section('content')
<div class="container">
			 <ol class="breadcrumb">
		  <li><a href="{{ URL::asset('/') }}">Home</a></li>
		  <li class="active">Payment Status</li>
		  <a href="{{ URL::asset('payment_status') }}"><p class="pull-right">Payment Status</p></a>
		 </ol>
<h1>ON-PROGRESS</h1>
</div>
@endsection
