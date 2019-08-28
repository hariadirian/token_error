@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/nouislider.min.css')}}" />
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
<div class="login" style="margin-top:30px;margin-bottom:30px">
	 <div class="container">
		 <!--<h2>Login</h2>-->
		 <div class="col-md-6 log" style="margin:0px">		
		 	<div style="padding:40px 40px 40px 100px;background:#fff;box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">
			 <h2>Sign in</h2>
			 <hr style="margin-right:80px"></hr>
			 	{!! Form::open(['route' => 'login']) !!}

					<h5>{!! Form::label('email', 'Email') !!}</h5>	
					{!! Form::email('email', null, ['class' => 'form-control', 'style' =>'width:80%;margin-bottom:20px;', 'placeholder' => 'Masukkan email...']) !!}

					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif

					<h5>{!! Form::label('password', 'Password') !!}</h5>
					{!! Form::password('password', ['class' => 'form-control', 'style' =>'width:80%;margin-bottom:20px;', 'placeholder' => 'Masukkan password...']) !!}		

					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
					
					<a class="btn btn-link" class="pull-right" href="{{ route('password.request') }}">
						{{ __('Forgot Your Password?') }}
					</a>
					@if( Request::segment(2) )
						{{ Form::hidden('cd_product', Request::segment(2)) }}
					@endif

					{!! Form::submit('Submit', ['class' => 'btn btn-primary form-control', 'style' => 'font-size:14px;width:80%']) !!}

					<div class="form-group row" style="margin-top:20px;text-align:center">
						<div class="col-md-10 offset-md-4">
							<a href="{{ url('/auth/google') }}" class="btn btn-google"><i class="fa fa-google"></i> Google</a>
							<a href="{{ url('/auth/twitter') }}" class="btn btn-twitter"><i class="fa fa-twitter"></i> Twitter</a>
							<a href="{{ url('/auth/facebook') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
						</div>
					</div>
								
					<!-- <a href="#">Forgot Password ?</a> -->
				{!! Form::close() !!}		
			</div>		 
		 </div>
		  <div class="col-md-6 login-right" style="padding-top:30px">
			  	<h3>NEW REGISTRATION</h3>
				<p>Tidak punya akun? Anda dapat langsung membuat akun dengan mendaftar <a href="{{ URL::asset('register') }}" style="color:blue"><u>di sini</u></a>.</p>
				
		 </div>
		 <div class="clearfix"></div>		 
		 
	 </div>
</div>
@endsection

@section('js')
	<script src="{{ asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('js/pages/frontend/_DetailTicket.js') }}"></script>
@endsection