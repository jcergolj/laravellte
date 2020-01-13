@extends('layouts.app')

@section('title')
    Create New Role
@endsection

@section('content-header')
<x-content-header>
    Create New Role
</x-content-header>
@endsection

@section('content')
<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Create New Role</h3>
        <a href="{{ route('roles.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        @livewire('roles.save-role')
    </x-slot>
</x-savings.content>
@endsection