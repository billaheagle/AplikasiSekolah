@extends('layouts.auth')
@section('title', 'Login')

@section('content')

<div class="vertical-align-wrap">
	<div class="vertical-align-middle auth-main">
		<div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/logo-white.svg" alt="Lucid">
            </div>
			<div class="card">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                <div class="header">
                    <p class="lead">Login to your account</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="signin-email" value="{{ old('email') }}" placeholder="Email">
                            @error('email')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="signin-password" placeholder="Password">
                            @error('password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group clearfix">
                            <label class="fancy-checkbox element-left">
                                <input type="checkbox">
                                <span>Remember me</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                        <div class="bottom">
                            <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="/forgot-password">Forgot password?</a></span>
                            <span>Don't have an account? <a href="/register">Register</a></span>
                        </div>
                    </form>
                </div>
            </div>
		</div>
	</div>
</div>

@stop
