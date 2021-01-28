@extends('layouts.auth')
@section('title', 'Forget Password')

@section('content')

<div class="vertical-align-wrap">
	<div class="vertical-align-middle auth-main">
		<div class="auth-box">
            <div class="top">
                <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Lucid">
            </div>
			<div class="card">
                <div class="header">
                    <p class="lead">Recover my password</p>
                </div>
                <div class="body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>Please enter your email address below to receive instructions for resetting password.</p>
                    <form class="form-auth-small" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">                                    
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="forgotpassword-email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="alert alert-danger mt-2">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">SEND PASSWORD RESET LINK</button>
                        <div class="bottom">
                            <span class="helper-text">Know your password? <a href="/login">Login</a></span>
                        </div>
                    </form>
                </div>
            </div>
		</div>
	</div>
</div>

@stop