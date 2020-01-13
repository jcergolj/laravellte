@props(['options', 'key', 'textField'])

<div class="input-group mb-3">
    <select
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        wire:model="{{ $key }}"
        value="{{ old($key) }}"
        placeholder="{{ trans("validation.attributes.$key") }}"
        {{ $attributes }}
    >
        <option value="">-- select --</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}">{{ $option->$textField }}</option>
        @endforeach
    </select>

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-cogs"></span>
        </div>
    </div>

    <x-inputs.error :id="$key" />
</div>
