@extends('layouts.app')

@section('title')
    Edit Existing Role
@endsection

@section('content-header')
<x-content-header>
    Edit Existing Role
</x-content-header>
@endsection

@section('content')
<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Edit Existing Role</h3>
        <a href="{{ route('roles.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <livewire:roles.save-role :role="$role" />
    </x-slot>
</x-savings.content>
@endsection