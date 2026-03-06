<div class="scrollbar-sidebar">
    <div class="app-sidebar__inner">
        <ul class="vertical-nav-menu">
            <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
                <a href="{{ route('home') }}" class="{{ request()->is('home') ? 'mm-active' : '' }}">
                    {{-- <i class="metismenu-icon pe-7s-graph2"></i> --}}
                    <i class="metismenu-icon fa fa-chart-line"></i>
                    Panel de Control
                </a>
            </li>

            {{-- <li class="{{ request()->is('company*') ? 'mm-active' : '' }}">
                <a href="{{ route('company.show', $company->id) }}" class="{{ request()->is('company*') ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-culture"></i>
                    Organización
                </a>
            </li> --}}

            <li class="{{ request()->is('company*') ? 'mm-active' : '' }}">
                <a href="{{ route('company.show') }}" class="{{ request()->is('company*') ? 'mm-active' : '' }}">
                    {{-- <i class="metismenu-icon pe-7s-culture"></i> --}}
                    <i class="metismenu-icon fa fa-building"></i>
                    Organización
                </a>
            </li>

            {{-- @can('user_company_index') --}}
            <li class="{{ request()->is('collaborators*') ? 'mm-active' : '' }}">
                <a href="{{ route('collaborators.index') }}" class="{{ request()->is('collaborators*') ? 'mm-active' : '' }}">
                    {{-- <i class="metismenu-icon pe-7s-science"></i> --}}
                    <i class="metismenu-icon fa fa-asterisk"></i>
                    {{-- <font-awesome-icon :icon="['fas', 'users-viewfinder']" /> --}}
                    Maestro
                </a>
            </li>
            {{-- @endcan --}}

            <hr>

            @can(['area_index'])
            <li class="{{ request()->is('areas*') ? 'mm-active' : '' }}">
                <a href="#">
                    <i class="metismenu-icon fa fa-cube"></i>
                    Módulos
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul>
                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Bienestar
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Procesos de Selección
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Ausentismo
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Planes de Formación
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Evaluación de Desempeño
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Dotación
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('user_company_index')
            <li class="{{ request()->is('users-company*') ? 'mm-active' : '' }}">
                <a href="{{ route('users_company.index') }}" class="{{ request()->is('users-company*') ? 'mm-active' : '' }}">
                    {{-- <i class="metismenu-icon pe-7s-users"></i> --}}
                    <i class="metismenu-icon fa fa-users"></i>
                    Usuarios
                </a>
            </li>
            @endcan

            <hr>

            <li>
                <a href="#">
                    {{-- <i class="metismenu-icon pe-7s-tools"></i> --}}
                    {{-- <i class="metismenu-icon fa fa-screwdriver-wrench"></i> --}}
                    <i class="metismenu-icon fa fa-toolbox"></i>
                    Herramientas
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul>
                    <li>
                        <a href="#">
                            <i class="metismenu-icon fa fa-toolbox"></i>
                            Control Horas Extras
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon"></i>
                            Inventario
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon"></i>
                            Tarjetas
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
    {{-- <img class="logo-mh-sidebar" src="../images/logo-mh.svg" alt="logo-muy-humano"> --}}
    {{-- <button class="btn btn-lg btn-primary btn-sidebar">
        <i class="metismenu-icon fa fa-toolbox"></i>
        Ver Tutoriales
    </button> --}}
</div>
