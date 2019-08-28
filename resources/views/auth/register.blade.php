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

<div class="login" style="margin-top:30px;margin-bottom:30px">
	 <div class="container">
		<div class="col-md-9 log" style="margin:0px;width:100%">
			<div style="padding:40px 100px 40px 100px;background:#fff;box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">

			<h2>Registration</h2>
			<hr style="margin-right:80px"></hr>
				{!! Form::open(['route' => 'register']) !!}
				<ul>
					<div class="col-sm-12">
						<div class="form-group form-left col-sm-4" style="padding-left:0">
							<li class="text-info">
								{!! Form::label('email', 'Email') !!}
							</li>
							<li>
								{!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Masukkan email...', 'maxlength' => 64]) !!}

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</li>
						</div>
						<div class="form-group form-left col-sm-4">
							<li class="text-info">
								{!! Form::label('password', 'Password') !!}
							</li>
							<li>
								{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Masukkan password...', 'maxlength' => 64]) !!}

								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</li>
						</div>
						<div class="form-group form-right col-sm-4" style="padding-right:0">
							<li class="text-info">
								{!! Form::label('password_confirmation', 'Masukkan Ulang Password') !!}
							</li>
							<li>
								{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Masukkan ulang password...', 'maxlength' => 64]) !!}

								@if ($errors->has('password_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
							</li>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group form-right col-sm-6" style="padding-left:0">
							<li class="text-info">
								{!! Form::label('first_name', 'Nama Depan') !!}
							</li>
							<li>
								{!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'Masukkan nama depan...', 'maxlength' => 64]) !!}

								@if ($errors->has('first_name'))
									<span class="help-block">
										<strong>{{ $errors->first('first_name') }}</strong>
									</span>
								@endif
							</li>
						</div>
						<div class="form-group form-right col-sm-6" style="padding-right:0">
							<li class="text-info">
								{!! Form::label('last_name', 'Nama Belakang') !!}
							</li>
							<li>
								{!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Masukkan nama belakang...', 'maxlength' => 64]) !!}

								@if ($errors->has('last_name'))
									<span class="help-block">
										<strong>{{ $errors->first('last_name') }}</strong>
									</span>
								@endif
							</li>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group form-right col-sm-6" style="padding-left:0">
							<li class="text-info">
								{!! Form::label('mobile_phone', 'Nomor HP') !!}
							</li>
							<li>
                <input type="number" name="mobile_phone" id="mobile_phone" class="form-control" min="3" max="999999999999" placeholder="Masukkan nomor handphone..." required>
								@if ($errors->has('mobile_phone'))
									<span class="help-block">
										<strong>{{ $errors->first('mobile_phone') }}</strong>
									</span>
								@endif
							</li>
						</div>
						<!-- <div class="form-group form-right col-sm-6" style="padding-right:0">
							<li class="text-info">
								{!! Form::label('id_card', 'KTP / SIM / No. Paspor') !!}
							</li>
							<li>
								{!! Form::text('id_card', null, ['class' => 'form-control', 'placeholder' => 'Masukkan nomor identitas...', 'maxlength' => 64]) !!}

								@if ($errors->has('id_card'))
									<span class="help-block">
										<strong>{{ $errors->first('id_card') }}</strong>
									</span>
								@endif
							</li>
						</div> -->
					</div>
					<!-- <div class="form-group form-right col-sm-12">
						<li class="text-info">
							{!! Form::label('address', 'Alamat') !!}
						</li>
						<li>
              <textarea name="address" id="address" class="form-control" rows="8"placeholder="Masukkan alamat..." style="resize: vertical;" required></textarea>
							@if ($errors->has('address'))
								<span class="help-block">
									<strong>{{ $errors->first('address') }}</strong>
								</span>
							@endif
						</li>
					</div> -->

					<div class="form-group">
            		<button type="submit" class="btn btn-primary form-control">Submit</button>
						<p class="click pull-right">By clicking this button, you agree to my modern style <a href="#">Policy Terms and Conditions</a> to Use</p>
					</div>
				</ul>
				{!! Form::close() !!}
			</div>
		</div>
		 <div class="col-md-6 reg-right">
			 <!-- <h3>Completely Free Accouent</h3>
			 <p>Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam, odio
			 libero tincidunt metus, sed euismod elit enim ut mi. Nulla porttitor et dolor sed condimentum. Praesent porttitor lorem dui, in pulvinar enim rhoncus vitae. Curabitur tincidunt, turpis ac lobortis hendrerit, ex elit vestibulum est, at faucibus erat ligula non neque.</p>
			 <h3 class="lorem">Lorem ipsum dolor sit amet elit.</h3>
			 <p>Tincidunt metus, sed euismod elit enim ut mi. Nulla porttitor et dolor sed condimentum. Praesent porttitor lorem dui, in pulvinar enim rhoncus vitae. Curabitur tincidunt, turpis ac lobortis hendrerit, ex elit vestibulum est, at faucibus erat ligula non neque.</p> -->
		 </div>
		 <div class="clearfix"></div>
	 </div>

</div>
@endsection

@section('js')
	<script src="{{ asset('frontend/js/main.js') }}"></script>
@endsection
