@props(['user', 'livewire'])

<li class="list-group-item" x-data="{ show: false}"  @close.window="show = false" x-cloak>
    <div class="row" x-show="!show" x-transition:enter="fade">
        {{ $element }}

        <div class="col-4 col-md-4">
            <a href="#" class="float-right" x-on:click="show = true">Edit</a>
        </div>
    </div>

    {{ $livewire }}
</li>
