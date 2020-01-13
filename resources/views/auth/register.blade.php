

@extends('layouts.guest-app')

@section('title')
    Register a new membership
@endsection

@section('content')
<div class="card">
    <div class="card-body register-card-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="../../index.html" method="post">
            @include('auth.form-elements._email')

            @include('auth.form-elements._password', ['key' => 'password_confirmation'])
        
            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Register" class="btn-primary" />
                </div>
            </div>
        </form>
        
        <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
    </div>
</div>
@endsection
