<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts._head')

<body
    class="hold-transition sidebar-mini"
    x-data="window.nav.make()"
    :class="{ 'sidebar-collapse' : collapsed }"
    x-on:resize.window="resize()"
    x-ref="body"
>

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        x-on:click="click()"
                        @click.away="clickAway()"
                        class="nav-link"
                        href="#"
                    >
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu" x-data="{ open: false }">
                    <a href="#" class="nav-link" x-on:click="open= true">
                        <img src="{{ auth()->user()->imageFile }}" class="user-image img-circle elevation-2" alt="User Image">
                        <span class="d-none d-md-inline">{{ auth()->user()->email }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" x-bind:class="{ 'show': open }" x-on:click.away="open= false" x-cloak>
                        <li class="user-header bg-primary">
                            <img src="{{ auth()->user()->imageFile }}" class="img-circle elevation-2">
                            <p>
                                {{ auth()->user()->email }}
                            </p>
                        </li>

                        <li class="user-footer">
                            <a href="{{ route('profile.users.index') }}" class="btn btn-default btn-flat">Profile</a>

                            <a
                                href="#"
                                class="btn btn-default btn-flat float-right"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                            >
                                {{ __('Sign Out') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4 x-cloak">
            <a href="{{ route('home.index') }}" class="brand-link">
                <i class="nav-icon fas fa-tachometer-alt elevation-3"></i>
                <span class="brand-text">Admin dashboard</span>
            </a>

            @can('for-route', [['manager']])
                <a href="{{ route('users.index') }}" class="brand-link">
                    <i class="nav-icon fas fa-user elevation-3"></i>
                    <span class="brand-text">Users</span>
                </a>
            @endcan

            @can('for-route')
                <a href="{{ route('roles.index') }}" class="brand-link">
                    <i class="nav-icon fas fa-users elevation-3"></i>
                    <span class="brand-text">Roles</span>
                </a>
            @endcan

        </aside>

        <div class="content-wrapper">

            <section class="content-header">
                @yield('content-header')
            </section>

            <section class="content">
                @include('layouts._flash')

                @yield('content')
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://jcergolj.me.uk">jcergolj</a>.</strong>
        </footer>
    </div>

    @livewireScripts

    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')

    @stack('scripts')
</body>

</html>
