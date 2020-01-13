<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link" x-show="!collapsed">
        <i class="nav-icon fas fa-times elevation-3" @click.prevent="collapsed = true; document.body.classList.remove('sidebar-open')"></i>
    </a>

    <a href="{{ route('home.index') }}" class="brand-link">
        <i class="nav-icon fas fa-tachometer-alt elevation-3"></i>
        <span class="brand-text">Admin dashboard</span>
    </a>

@can('by-roles', [['admin']])
    <a href="{{ route('users.index') }}" class="brand-link">
        <i class="nav-icon fas fa-user elevation-3"></i>
        <span class="brand-text">Users</span>
    </a>
    <a href="{{ route('roles.index') }}" class="brand-link">
        <i class="nav-icon fas fa-users elevation-3"></i>
        <span class="brand-text">Roles</span>
    </a>
@endcan
    
</aside>    
