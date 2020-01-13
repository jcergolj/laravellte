@section('title')
    Server Error
@endsection

@extends('errors.app')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Server Error</h3>
        <p>
            {{ $exception->getMessage() ?: 'Something went wrong on our end.' }}
        </p>
    </div>
</div>
