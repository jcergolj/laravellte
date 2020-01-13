@props(['key'])

<div class="input-group mb-3">
    <input
        type="password"
        name="{{ $key }}"
        wire:model="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
        required
    >
    
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
    </div>

    <x-inputs.error :id="$key" />
</div>

