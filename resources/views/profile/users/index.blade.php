@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="offset-md-4 col-md-4">

            <div class="card card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img
                            class="profile-user-img img-fluid img-circle"
                            src="{{ $user->image_file }}"
                        />
                    </div>

                    <h3 class="profile-username text-center">{{ $user->email }}</h3>

                    <ul class="list-group list-group-unbordered mb-3">
                        <x-profile.element :user="$user">
                            <x-slot name="element">
                                <div class="col-md-2">
                                    <b>Email</b>
                                </div>
                                <div class="col-md-6">
                                    {{ $user->email }}
                                </div>
                            </x-slot>

                            <x-slot name="livewire">
                                <livewire:profile.email />
                            </x-slot>
                        </x-profile.element>

                        <x-profile.element :user="$user">
                            <x-slot name="element">
                                <div class="col-md-2">
                                    <b>Password</b>
                                </div>
                                <div class="col-md-6">
                                    ***************
                                </div>
                            </x-slot>

                            <x-slot name="livewire">
                                <livewire:profile.password />
                            </x-slot>
                        </x-profile.element>

                        @include('profile.users._image')
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
