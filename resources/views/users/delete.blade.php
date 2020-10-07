<div>
    @can('for-route', [['manager'], $this->user])
        @if(!$user->isHimself(auth()->user()))
            <a
                x-data
                x-on:click.prevent="confirm('Are you sure?') && $wire.destroy()"
                href="#"
                class="btn-default"
            >
                <span class='fa fa-times'></span>
            </a>
        @endif
    @endcan
</div>
