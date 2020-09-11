@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@if (session('flash'))
    <div class="alert alert-{{ session('flash')['level'] }}" role="alert" x-data="{show : true}" x-show="show" x-init="setTimeout(() => { show = false; }, 3500);">
        {{ session('flash')['message'] }}
    </div>
@endif
<div
    x-data="{show : false}"
    x-cloak
    @flash.window="
        show = true;
        $el.innerHTML = $event.detail.message;
        $el.classList.add($event.detail.level);
        $el.classList.add('alert');
        setTimeout(() => { show = false; }, 3500);
    "
    class="alert"
    role="alert"
    x-show="show"
>
</div>
