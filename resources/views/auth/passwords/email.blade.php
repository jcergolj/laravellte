@extends('layouts.guest-app')

@section('title')
    Reset Password
@endsection

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Reset Password') }}</p>

        @include('layouts._flash')

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-inputs.email required="required" autofocus />

            <div class="row">
                <div class="offset-4 col-8">
                    <x-inputs.button text="__('Send Password Reset Link')" class="btn-primary" />
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">{{ __('Sing In') }}</a>
        </p>
    </div>
</div>
@endsection
