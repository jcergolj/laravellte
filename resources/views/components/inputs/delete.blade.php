@props(['entity'])

<a
    href="#"
    class="btn-default"
    data-entity-id="{{ $entity->id }}"
    @click.prevent="$refs.modal.classList.add('d-block'); deleteId=event.target.parentNode.getAttribute('data-entity-id'); "
    @close.window="$refs.modal.classList.remove('d-block')"
>
    <span class='fa fa-times'></span>
</a>
