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
      <div class="dropdown me-2">
        <li class="nav-item dropdown user-menu" style="list-style: none;">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <img src="../assets/img/user4-128x128.jpg" class="user-image rounded-circle shadow" alt="User Image"
              style="width:50px;height:50px;border-radius:6px;object-fit:cover;" />
            <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
          </a>
          </a>

          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
            <li class="user-header text-bg-primary">

              <div class="d-flex align-items-center">

                <!-- FOTO IZQUIERDA -->
                <img src="../assets/img/user4-128x128.jpg" class="rounded-circle shadow me-3" alt="User Image"
                  style="width: 70px; height: 70px;" />

                <!-- INFO A LA DERECHA -->
                <div class="d-flex flex-column text-start">

                  <!-- Nombre -->
                  <span class="fw-bold">{{ auth()->user()->name ?? 'Usuario' }}</span>

                  <!-- Fecha -->
                  <span class="text-light small">
                    {{ auth()->user()->created_at->format('d/m/Y') }}
                  </span>

                </div>

              </div>

            </li>

        </li>
        <li class="user-footer">
          <div class="d-flex justify-content-between align-items-center">

            <a class="btn btn-sm btn-outline-success" href="{{ route('perfil.edit') }}">
              Ajustes
            </a>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button class="btn btn-sm btn-danger" type="submit">
                <i class="bi bi-box-arrow-right"></i> Salir
              </button>
            </form>

          </div>
        </li>

        </ul>
        </li>
        </li>

      </div>
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