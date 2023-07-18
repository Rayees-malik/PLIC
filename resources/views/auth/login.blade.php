@extends('layouts.app')

@section('page', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <h2 class="card-header">Login</h2>

                <div class="card-body">
                  <form method="POST" action="{{ route('login') }}">
                      <div class="container">
                          @csrf
                          <div class="row">
                            <div class="input-wrap{{ $errors->has('email') ? ' input-danger' : '' }} col-12">
                                <label for="email">Email Address</label>
                                <div class="icon-input">
                                    <i class="material-icons pre-icon">email</i>
                                    <input id="email" type="text" name="email" value="{{ old('email') }}" class="js-allow-submit" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                <small class="info-danger">{{ $errors->first('email') }}</small>
                                @endif
                            </div>
                            <div class="input-wrap{{ $errors->has('password') ? ' input-danger' : '' }} col-12">
                                <label for="password">Password</label>
                                <div class="icon-input">
                                    <i class="material-icons pre-icon">vpn_key</i>
                                    <input id="password" type="password" name="password" class="js-allow-submit" required>
                                </div>
                                @if ($errors->has('password'))
                                <small class="info-danger">{{ $errors->first('password') }}</small>
                                @endif
                            </div>
                          </div>

                          <button type="submit" class="primary-btn block-btn mt-3">
                              Login
                          </button>

                          @if (Route::has('password.request'))
                          <a class="link-btn mt-3" href="{{ route('password.request') }}">
                              Forgot Your Password?
                          </a>
                          @endif
                      </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
