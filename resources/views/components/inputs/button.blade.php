@props(['text'])

<button type="submit" {{ $attributes->merge(['class' => 'btn btn-block']) }}>{{ $text }}</button>
