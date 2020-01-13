@section('title')
    Unauthorized
@endsection

@extends('errors.app')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Unauthorized</h3>
        <p>
            {{ $exception->getMessage() ?: 'You are not authorized.' }}
        </p>
    </div>
</div>
