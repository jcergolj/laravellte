@section('title')
    Forbidden
@endsection

@extends('errors.app')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Forbidden</h3>
        <p>
            {{ $exception->getMessage() ?: 'You are not allowed to see this page.' }}
        </p>
    </div>
</div>

@endsection
