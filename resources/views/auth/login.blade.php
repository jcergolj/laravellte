@extends('layouts.guest-app')

@section('title')
    Sign In
@endsection

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="login">
            @csrf

            <x-inputs.email required="required" autofocus />

            <x-inputs.password />

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">{{ trans('validation.attributes.remember_me') }}</label>
                    </div>
                </div>

                <div class="col-4">
                    <x-inputs.button text="Sign In" class="btn-primary" />
                </div>
            </div>
        </form>

        <p class="mb-1">
            <a href="{{ route('password.request') }}">I forgot my password</a>
        </p>
    </div>
</div>

@endsection
