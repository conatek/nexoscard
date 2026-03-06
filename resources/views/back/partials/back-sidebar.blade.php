<div class="scrollbar-sidebar">
    <div class="app-sidebar__inner">
        <ul class="vertical-nav-menu">
            @can('home_master')
                <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
                    <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Panel de Control
                    </a>
                </li>
            @elsecan('home_super')
                <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
                    <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Panel de Control
                    </a>
                </li>
            @elsecan('home_admin')
                <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
                    <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Panel de Control
                    </a>
                </li>
            @else
                <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
                    <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Panel de Control
                    </a>
                </li>
            @endcan

            @can('user_index')
            <li class="{{ request()->is('users*') ? 'mm-active' : '' }}">
                <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-users"></i>
                    Usuarios
                </a>
            </li>
            @endcan

            @can('company_index')
            <li class="{{ request()->is('companies*') ? 'mm-active' : '' }}">
                <a href="{{ route('companies.index') }}" class="{{ request()->is('companies*') ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-culture"></i>
                    Empresas
                </a>
            </li>
            @endcan

            @can('role_index')
            <li class="{{ request()->is('roles*') ? 'mm-active' : '' }}">
                <a href="{{ route('roles.index') }}" class="{{ request()->is('roles*') ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-airplay"></i>
                    Roles
                </a>
            </li>
            @endcan

            @can('permission_index')
                <li class="{{ request()->is('permissions*') ? 'mm-active' : '' }}">
                    <a href="{{ route('permissions.index') }}" class="{{ request()->is('permissions*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-key"></i>
                        Permisos
                    </a>
                </li>
            @endcan

            @can('cms_index')
{{--            <li class="app-sidebar__heading">Roles Y Permisos</li>--}}
            <li class="{{ request()->is('cms*') ? 'mm-active' : '' }}">
                <a href="#">
                    <i class="metismenu-icon pe-7s-news-paper"></i>
                    CMS
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul>
                    <li>
{{--                        <a href="{{ route('cms.index') }}" class="{{ request()->is('companies') ? 'mm-active' : '' }}">--}}
                        <a href="#">
                            <i class="metismenu-icon"></i>
                            Listado
                        </a>
                    </li>
                    <li>
{{--                        <a href="{{ route('cms.create') }}" class="{{ request()->is('companies/create') ? 'mm-active' : '' }}">--}}
                        <a href="#">
                            <i class="metismenu-icon"></i>
                            Agregar
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
        </ul>
    </div>
</div>
