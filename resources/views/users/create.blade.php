@extends('layouts.app')

@section('title')
    Create New User
@endsection

@section('content-header')
<x-content-header>
    Create New User
</x-content-header>
@endsection

@section('content')
<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Create New User</h3>
        <a href="{{ route('users.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <livewire:users.save-user />
    </x-slot>
</x-savings.content>
@endsection