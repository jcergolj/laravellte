@extends('layouts.app')

@section('title')
    Edit Existing User
@endsection

@section('content-header')
<x-content-header>
    Edit Existing User
</x-content-header>
@endsection

@section('content')
<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Edit Existing User</h3>
        <a href="{{ route('users.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <livewire:users.save-user :user="$user" />
    </x-slot>
</x-savings.content>
@endsection
