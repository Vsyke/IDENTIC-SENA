<header class="app-header navbar navbar-expand navbar-dark bg-transparent px-3" style="backdrop-filter: blur(6px);">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-sm btn-outline-secondary me-2 d-lg-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('assets/logo-chad.png') }}" alt="logo"
          style="width:50px;height:50px;border-radius:6px;object-fit:cover;">
        <span class="brand-text fw-light neon">{{ config('app.name', 'Mi Sistema') }}</span>
      </a>
      <small class="small-muted ms-2 d-none d-md-inline">Panel administrativo</small>
    </div>


    <div class="d-flex align-items-center gap-2">
      {{-- El menú del usuario (Dropdown) --}}
      <li class="nav-item dropdown user-menu d-flex align-items-center" style="list-style: none;">
        <a href="#" class="nav-link dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../assets/img/user4-128x128.jpg" class="user-image rounded-circle shadow" alt="User Image"
            style="width:40px;height:40px;object-fit:cover;" />
          <span class="d-none d-md-inline ms-2 me-1">{{ auth()->user()->name ?? 'Usuario' }}</span>
        </a>

        {{-- Menú Desplegable --}}
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          {{-- Encabezado del usuario (Se mantiene igual, está bien) --}}
          <li class="user-header text-bg-success">
            {{-- ... (Contenido de imagen y texto aquí) ... --}}
            <div class="d-flex align-items-center">
              <img src="../assets/img/user4-128x128.jpg" class="rounded-circle shadow me-3" alt="User Image"
                style="width: 70px; height: 70px; border: 3px solid rgba(255, 255, 255, 0.5);" />
              <div class="d-flex flex-column text-start">
                <span class="fw-bold fs-5">{{ auth()->user()->roles->first()->name ?? 'Administrador' }}</span>
                <span class="fw-normal text-light small">{{ auth()->user()->name ?? 'Usuario' }}</span>
                <span class="text-light small mt-1">
                  Miembro desde: {{ auth()->user()->created_at->format('d/m/Y') }}
                </span>
              </div>
            </div>
          </li>

          
          <li class="user-footer p-2">
            <div class="d-flex justify-content-between align-items-center">

             
              <form action="{{ route('perfil.edit') }}" method="GET">
                
                <button class="btn btn-sm shadow" type="submit"
                  style="background-color: #8dd9c6; color: #198754; font-weight: 500;">
                  Ajustes
                </button>
              </form>

              
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-danger shadow" type="submit">
                  <i class="bi bi-box-arrow-right"></i> Salir
                </button>
              </form>

            </div>
          </li>
        </ul>
      </li>
    </div>
  </div>
</header>

<script>
  // sidebar toggle (mobile)
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.app-sidebar');
    btn?.addEventListener('click', () => {
      sidebar?.classList.toggle('collapsed');
    });
  });
</script>