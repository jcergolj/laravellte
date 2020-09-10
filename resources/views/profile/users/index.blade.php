@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="offset-lg-4 col-lg-5 col-md-6 offset-md-5">

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
                                <div class="col-md-12">
                                    <b>Email</b>
                                </div>
                                <div class="col-8 col-md-8">
                                    {{ $user->email }}
                                </div>
                            </x-slot>

                            <x-slot name="livewire">
                                <livewire:profile.update-email />
                            </x-slot>
                        </x-profile.element>

                        <x-profile.element :user="$user">
                            <x-slot name="element">
                                <div class="col-md-12">
                                    <b>Password</b>
                                </div>
                                <div class="col-8 col-md-8">
                                    ***************
                                </div>
                            </x-slot>

                            <x-slot name="livewire">
                                <livewire:profile.update-password />
                            </x-slot>
                        </x-profile.element>

                        <x-profile.element :user="$user">
                            <x-slot name="element">
                                <div class="col-md-12">
                                    <b>Image</b>
                                </div>
                                <div class="col-8 col-md-8">
                                <img
                                    class="profile-user-img img-fluid img-circle"
                                    src="{{ $user->image_file }}"
                                />
                                </div>
                            </x-slot>

                            <x-slot name="livewire">
                                <livewire:profile.update-image />
                            </x-slot>
                        </x-profile.element>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
