@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@if (session('flash'))
    <div class="alert alert-{{ session('flash')['level'] }}" role="alert">
        {{ session('flash')['message'] }}
    </div>
@endif

<div x-data="{show : false}" x-cloak>
    <div
        class="alert"
        role="alert"
        x-show="show"
        @flash.window="
            show = true;
            $el.innerHTML = $event.detail.message;
            $el.classList.add($event.detail.level);
            $el.classList.add('alert');
        "
    >
    </div>
</div>
