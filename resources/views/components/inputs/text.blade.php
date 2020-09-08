@props(['key'])

<div class="input-group mb-3">
    <input
        {{ $attributes }}
        wire:model.defer="{{ $key }}"
        type="text"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
    >

    <x-inputs.fa fontAwesome="fa-edit" />

    <x-inputs.error field="{{ $key }}" />
</div>
