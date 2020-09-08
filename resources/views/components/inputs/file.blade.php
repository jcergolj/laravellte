@props(['key'])

<div class="input-group mb-3">
    <input
        {{ $attributes }}
        wire:model.defer="{{ $key }}"
        type="file"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        style="border: none"
        placeholder="{{ trans("validation.attributes.$key") }}"
    >

    <x-inputs.fa fontAwesome="fa-file" />

    <x-inputs.error field="{{ $key }}" />
</div>
