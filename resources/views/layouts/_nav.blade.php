<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a 
                class="nav-link"
                x-on:click="collapsed = !collapsed; windowWidthCollapse()"
                href="#"
                data-turbolinks="false"
            >
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu" x-data="{ open: false }">
            <a href="#" class="nav-link" x-on:click="open= true" data-turbolinks="false">
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
