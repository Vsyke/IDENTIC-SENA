@extends('plantilla.app')

@section('contenido')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <div class="card shadow-lg animate__animated animate__fadeIn" style="background: rgba(11, 18, 32, 0.7) !important; border: 1px solid rgba(16, 185, 129, 0.2) !important; backdrop-filter: blur(12px);">
                <div class="card-body p-5 text-center">
                    
                    <div class="mb-4">
                        <i class="bi bi-shield-check text-green" style="font-size: 3rem; filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4));"></i>
                        <h1 class="text-white mt-3 mb-1">Panel de <span class="neon">Vigilancia</span></h1>
                        <p class="small-muted fs-5">Terminal de Control de Acceso - Identic</p>
                    </div>
                    
                    <hr style="border-color: rgba(16, 185, 129, 0.15); width: 60%; margin: 2rem auto;">

                    <div class="py-3">
                        <h3 class="text-white fw-light">Bienvenido, <span class="text-green fw-bold">{{ auth()->user()->name }}</span></h3>
                        <p class="small-muted mt-3 mx-auto" style="max-width: 500px;">
                            Has ingresado al panel operativo de seguridad. Desde aquí puedes gestionar el flujo de usuarios y equipos del centro.
                        </p>
                    </div>

                    <div class="mt-5 p-4 rounded-3" style="background: rgba(16, 185, 129, 0.05); border: 1px dashed rgba(16, 185, 129, 0.3);">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-left-circle text-green me-3 fs-4"></i>
                            <p class="mb-0 text-white">
                                Por favor, utiliza el <strong>menú lateral</strong> para acceder a las funciones de escaneo, reportes y gestión de asistencia.
                            </p>
                        </div>
                    </div>

                    <div class="row mt-5 pt-3">
                        <div class="col-6 col-md-4">
                            <small class="small-muted d-block text-uppercase mb-1">Estado del Sistema</small>
                            <span class="badge rounded-pill bg-success-soft text-green border border-success">OPERATIVO</span>
                        </div>
                        <div class="col-6 col-md-4">
                            <small class="small-muted d-block text-uppercase mb-1">Fecha Actual</small>
                            <span class="text-white">{{ date('d/m/Y') }}</span>
                        </div>
                        <div class="col-12 col-md-4 mt-3 mt-md-0 text-md-end">
                            <small class="small-muted d-block text-uppercase mb-1">Soporte Técnico</small>
                            <span class="text-white">Sykron S.A.S</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Estilos locales que complementan tu theme-green.css */
    .bg-success-soft {
        background: rgba(16, 185, 129, 0.1);
    }
    
    .neon {
        color: #26f3b6;
        text-shadow: 0 0 15px rgba(0, 230, 118, 0.3);
    }

    /* Animación sutil de entrada */
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        border-color: rgba(16, 185, 129, 0.4) !important;
    }
</style>
@endsection