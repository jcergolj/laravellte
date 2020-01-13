@props(['entity'])

<a
    @click.prevent="
        $refs.modal.classList.add('d-block');
        deleteId={{ $entity->id }};
    "
    @close.window="$refs.modal.classList.remove('d-block')"
    href="#"
    class="btn-default"
>
    <span class='fa fa-times'></span>
</a>
