@props(['key'])

<div class="input-group mb-3">
    <input
        type="email"
        name="email"
        class="form-control @errorClass('email')"
        wire:model="email"
        value="{{ old('email') }}"
        placeholder="{{ trans('validation.attributes.email') }}"
        {{ $attributes }}
    >

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-envelope"></span>
        </div>
    </div>

    <x-inputs.error :id="'email'" />
</div>
