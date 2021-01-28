@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')

<div class="vertical-align-wrap">
	<div class="vertical-align-middle auth-main">
		<div class="auth-box">
            <div class="top">
                <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Lucid">
            </div>
			<div class="card">
                <div class="header">
                    <p class="lead">Reset password</p>
                </div>
                <div class="body">
                	 @if (session('status'))
			            <div class="alert alert-success" role="alert">
			                {{ session('status') }}
			            </div>
		            @endif
                    <form class="form-auth-small" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="resetpassword-email" placeholder="Your Email" value="{{ $request->email ?? old('email') }}" required autofocus readonly>
                            @error('email')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="resetpassword-password" placeholder="New Password" required autocomplete="new-password">
                            @error('password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>    
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" id="resetpassword-password-confirmation" placeholder="Password Confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">RESET PASSWORD</button>
                    </form>
                </div>
            </div>
		</div>
	</div>
</div>

@stop