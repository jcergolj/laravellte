@props(['options', 'key', 'textField'])

<div class="input-group mb-3">
    <select
        {{ $attributes }}
        wire:model.defer="{{ $key }}"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        value="{{ old($key) }}"
        placeholder="{{ trans("validation.attributes.$key") }}"
    >
        <option value="">-- select --</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}">{{ $option->$textField }}</option>
        @endforeach
    </select>

    <x-inputs.fa fontAwesome="fa-cogs" />

    <x-inputs.error field="{{ $key }}" />
</div>
