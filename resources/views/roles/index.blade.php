@extends('layouts.app')

@section('title')
    Roles
@endsection

@section('content-header')
<x-content-header>
    Roles
</x-content-header>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Roles</h3>
                <a href="{{ route('roles.create') }}" class="float-right">Add New</a>
            </div>

            <livewire:roles.table-role />
        </div>
    </div>
</div>
@endsection