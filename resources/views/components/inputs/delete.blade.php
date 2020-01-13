@props(['entity'])

<a
    href="#"
    class="btn-default"
    @click.prevent="
        $refs.modal.classList.add('d-block');
        deleteId={{ $entity->id }};
    "
    @close.window="$refs.modal.classList.remove('d-block')"
>
    <span class='fa fa-times'></span>
</a>
