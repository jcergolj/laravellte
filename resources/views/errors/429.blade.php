@section('title')
    Too many requests
@endsection

@extends('errors.app')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Too many requests</h3>
        <p>
            {{ $exception->getMessage() ?: 'Too many requests.' }}
        </p>
    </div>
</div>
