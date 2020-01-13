@extends('layouts.guest-app')

@section('title')
    Create New Account
@endsection

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Create New Account</p>

        <livewire:accepted-invitation :user="$user" />
    </div>
</div>
@endsection
