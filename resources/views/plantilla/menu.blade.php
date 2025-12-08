<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/logo-green.png') }}" 
                 alt="Sistema Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light neon">{{ config('app.name', 'Mi Sistema') }}</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link" id="itemDashboard">
                        <i class="nav-icon bi bi-speedometer text-green"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- AULAS -->
                <li class="nav-item">
                    <a href="{{ route('aulas.index') }}" class="nav-link">
                        <i class="bi bi-door-closed text-green"></i>
                        <p>Aulas</p>
                    </a>
                </li>

                <!-- FICHAS -->
                <li class="nav-item">
                    <a href="{{ route('fichas.index') }}" class="nav-link">
                        <i class="bi bi-collection-fill text-green"></i>
                        <p>Fichas</p>
                    </a>
                </li>

                <!-- SEGURIDAD -->
                <li class="nav-item" id="mnuSeguridad">
                    <a href="#" class="nav-link">
                        <i class="bi bi-shield-lock-fill text-green"></i>
                        <p>
                            Seguridad
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @can('roles_permisos_list')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link" id="itemRoles">
                                <i class="bi bi-ui-checks-grid"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan

                        @can('users_list')
                        <li class="nav-item">
                            <a href="{{ route('usuarios.index') }}" class="nav-link" id="itemUsuarios">
                                <i class="bi bi-people-fill"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>

            </ul>

        </nav>
    </div>

</aside>
