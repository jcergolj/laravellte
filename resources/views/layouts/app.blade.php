<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts._head')

<body
    class="hold-transition sidebar-mini"
    x-data="{ collapsed : false}"
    :class="{ 'sidebar-collapse' : collapsed}"
>

    <div class="wrapper">
        @include('layouts._nav')

        @include('layouts._aside')
    
        <div class="content-wrapper">

            <section class="content-header">
                @yield('content-header')
            </section>

            <section class="content">
                @include('layouts._flash')

                @yield('content')
            </section>
        </div>

        @include('layouts._footer')
    </div>    

    @livewireScripts

    <script src="{{ asset('js/app.js') }}"></script>
    
    @yield('scripts')

    @stack('scripts')
</body>

</html>
