@props(['key'])

<div class="input-group mb-3">
    <input
        type="text"
        name="{{ $key }}"
        wire:model.lazy="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
        {{ $attributes }}
    >
    
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-edit"></span>
        </div>
    </div>

    <x-inputs.error :id="$key" />
</div>
