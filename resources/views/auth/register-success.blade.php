@extends('layouts.frontend-skeleton')

@section('css')

    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection
@section('content')

<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="#">Home</a></li>
			<li class="active">Login</li>
		</ul>
	</div>
</div>
<div class="registration-form" style="margin-top:30px;margin-bottom:30px">
	 <div class="container">
		<div class="col-md-9 log" style="margin:0px;width:100%">		
			<div style="padding:40px 100px 40px 100px;height:300px;background:#fff;box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">
		 		<h2>Registration</h2>
				<hr></hr>
				<div class="col-md-12 reg-form">
					<div class="reg">
						<p>Anda berhasil mendaftar, selanjutnya aktifkan akun anda dengan mengklik link aktivasi yang dikirimkan melalui email anda.</p>
					</div>
				</div>
			</div>
		 </div>
		 <div class="clearfix"></div>		 
	 </div>
</div>
@endsection

@section('js')
	<script src="{{ asset('frontend/js/main.js') }}"></script>
@endsection