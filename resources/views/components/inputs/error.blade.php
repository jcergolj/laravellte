@props(['id'])

@error($id)
    <span class="error">{{ $message }}</span>
@else
    <span id="{{ $id }}" class="error d-none"></span>
@enderror