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
                {{-- VIGILANTE: Aquí defines qué ve específicamente el vigilante --}}
                @if(auth()->user()->hasRole('vigilante') || auth()->user()->hasRole('admin'))
                <li class="nav-item">
                    <a href="{{ route('asistencias.personasQR') }}" class="nav-link">
                        <i class="bi bi-qr-code-scan text-green"></i>
                        <p>Escanear QR De Personas</p>
                    </a>
                </li>
                @endif

                {{-- ADMINISTRACIÓN: Aulas y Fichas (Solo para personal administrativo/profesores, NO vigilantes ni estudiantes) --}}
                @if(!auth()->user()->hasRole('estudiante') && !auth()->user()->hasRole('vigilante'))
                <li class="nav-item">
                    <a href="{{ route('aulas.index') }}" class="nav-link">
                        <i class="bi bi-door-closed text-green"></i>
                        <p>Aulas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('fichas.index') }}" class="nav-link">
                        <i class="bi bi-collection-fill text-green"></i>
                        <p>Fichas</p>
                    </a>
                </li>

                {{-- SEGURIDAD --}}
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
                @endif

            </ul>
        </nav>
    </div>
</aside>