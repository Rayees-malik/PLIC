@extends('layouts.app')

@section('page', 'Reset Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Reset Password</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

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

                        <div class="col-12">
                            <button type="submit" class="primary-btn block-btn mt-3">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
