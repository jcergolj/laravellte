@extends('layouts.app')

@section('title')
    Users
@endsection

@section('content-header')
<x-content-header>
    Users
</x-content-header>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Users</h3>
                <a href="{{ route('users.create') }}" class="float-right">Add New</a>
            </div>

            <livewire:users.table-user />
        </div>
    </div>
</div>
@endsection