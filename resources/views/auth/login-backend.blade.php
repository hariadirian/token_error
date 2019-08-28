@extends('layouts.login-backend-skeleton')

@section('content')
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);" style="color:#1e5992;">TMII  <b>e-Ticketing</b></a>
        <small style="color:#0082ff;">Backend Application</small>
    </div>
    <div class="card">
        <div class="body">
            <form class="form-horizontal" method="POST" action="{{ route('backend') }}">
                {{ csrf_field() }}
                <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                    <div class="form-line">
                        <input id="username" placeholder="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password"  required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8 p-t-5">
                         <input class="filled-in chk-col-pink" type="checkbox" id="rememberme" name="remember" {{ old('remember') ? 'checked' : '' }}> 
                        <label for="rememberme">Remember Me</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-primary waves-effect" type="submit">SIGN IN</button>
                    </div>
                </div>
                <!-- <div class="row m-t-15 m-b--20">
                    <div class="col-xs-6">
                        <a href="sign-up.html">Register Now!</a>
                    </div>
                    <div class="col-xs-6 align-right">
                        <a  href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </div> -->
            </form>
        </div>
    </div>
</div>
@endsection