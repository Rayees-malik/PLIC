@extends('layouts.app')

@section('page', 'Reset Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Reset Password</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="input-wrap{{ $errors->has('email') ? ' input-danger' : '' }} col-12">
                            <label for="email">Email Address</label>
                            <div class="icon-input">
                                <i class="material-icons pre-icon">email</i>
                                <input id="email" type="text" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                            </div>
                            @if ($errors->has('email'))
                            <small class="info-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        <div class="input-wrap{{ $errors->has('password') ? ' input-danger' : '' }} col-12">
                            <label for="password">Password</label>
                            <div class="icon-input">
                                <i class="material-icons pre-icon">vpn_key</i>
                                <input id="password" type="password" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                            <small class="info-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>
                        <div class="input-wrap col-12">
                            <label for="password">Confirm Password</label>
                            <div class="icon-input">
                                <i class="material-icons pre-icon">vpn_key</i>
                                <input id="password-confirm" type="password" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="primary-btn block-btn mt-3">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
