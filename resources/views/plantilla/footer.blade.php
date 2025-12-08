<footer class="app-footer mt-auto py-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <div class="small-muted">
      &copy; {{ date('Y') }} {{ config('app.name', 'Mi Sistema') }}. All rights reserved.
    </div>

    <div class="small-muted">
      <!-- Puedes cambiar esto por tu empresa o eliminar -->
      <span>Diseñado por <strong>SENA - Adso</strong></span>
    </div>
  </div>
</footer>

<style>
  .app-footer {
    background: transparent;
    border-top: 1px solid rgba(255,255,255,0.03);
    color: #cfeee0;
  }
  .app-footer .small-muted { color: #9fcfb6; font-size: .9rem; }
</style>
