@props(['field'])

@error($field)
    <span class="error">{{ $message }}</span>
@enderror