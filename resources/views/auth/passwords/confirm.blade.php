@extends('layouts.guest-app')

@section('title')
    Password Confirmation
@endsection

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Please confirm your password before continuing.') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <x-inputs.password autofocus />

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <x-inputs.button text="trans('validation.attributes.password_confirmation')" class="btn-primary" />

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
