@section('title')
    Not Found
@endsection

@extends('errors.app')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
        <p>
            We could not find the page you were looking for.
            Would you like to go to <a href="{{ route('home.index') }}">return to dashboard</a>?
        </p>
    </div>
</div>

@endsection
