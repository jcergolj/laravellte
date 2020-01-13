<aside class="main-sidebar sidebar-dark-primary elevation-4 x-cloak">
    <a href="{{ route('home.index') }}" class="brand-link">
        <i class="nav-icon fas fa-tachometer-alt elevation-3"></i>
        <span class="brand-text">Admin dashboard</span>
    </a>

@can('for-route', [['users.index']])
    <a href="{{ route('users.index') }}" class="brand-link">
        <i class="nav-icon fas fa-user elevation-3"></i>
        <span class="brand-text">Users</span>
    </a>
@endcan

@can('for-route', [['roles.index']])
    <a href="{{ route('roles.index') }}" class="brand-link">
        <i class="nav-icon fas fa-users elevation-3"></i>
        <span class="brand-text">Roles</span>
    </a>
@endcan
    
</aside>    
