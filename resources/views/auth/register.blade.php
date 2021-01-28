@extends('layouts.auth')
@section('title', 'Register')

@section('content')

<div class="vertical-align-wrap">
	<div class="vertical-align-middle auth-main">
		<div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/logo-white.svg" alt="Lucid">
            </div>
			<div class="card">
                <div class="header">
                    <p class="lead">Create an account</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" id="signup-name" placeholder="Your Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="signup-email" placeholder="Your Email" value="{{ old('email') }}">
                            @error('email')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="signup-password" placeholder="Password">
                            @error('password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>    
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" id="signup-password-confirmation" placeholder="Password Confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">REGISTER</button>
                        <div class="bottom">
                            <span class="helper-text">Already have an account? <a href="/login">Login</a></span>
                        </div>
                    </form>
                    <div class="separator-linethrough"><span>OR</span></div>
                    <button class="btn btn-signin-social"><i class="fa fa-facebook-official facebook-color"></i> Sign in with Facebook</button>
                    <button class="btn btn-signin-social"><i class="fa fa-twitter twitter-color"></i> Sign in with Twitter</button>
                </div>
            </div>
		</div>
	</div>
</div>

@stop