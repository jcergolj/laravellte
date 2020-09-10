@props(['entity'])

<a
    x-on:click.prevent="{ showModal = true; deleteId = '{{ $entity->id }}' }"
    href="#"
    class="btn-default"
>
    <span class='fa fa-times'></span>
</a>
