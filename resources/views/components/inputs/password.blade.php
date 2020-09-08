@props(['key' => 'password'])

<div class="input-group mb-3">
    <input
        wire:model.defer="{{ $key }}"
        type="password"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
        required
    >

    <x-inputs.fa fontAwesome="fa-lock" />

    <x-inputs.error field="{{ $key }}" />
</div>
