@extends('plantilla.app')

@section('contenido')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            {{-- Card Principal --}}
            <div class="card shadow-lg" style="background: rgba(3,7,12,0.6); border: 1px solid rgba(16,185,129,0.3); backdrop-filter: blur(10px);">
                <div class="card-body p-4 text-center">
                    <h2 class="text-white mb-1">Identic <span class="text-green neon">Pass</span></h2>
                    <p class="text-muted small">ID Digital de Asistencia</p>
                    
                    <hr style="border-color: rgba(16,185,129,0.2);">

                    {{-- Contenedor del QR --}}
                    <div class="qr-wrapper my-4 p-3 rounded-3" style="background: #fff; display: inline-block; box-shadow: 0 0 25px rgba(16,185,129,0.2);">
                        {!! QrCode::size(220)->margin(1)->generate($user->qr_token) !!}
                    </div>

                    {{-- Información del Estudiante --}}
                    <div class="mt-3">
                        <h4 class="text-green fw-bold mb-0">{{ auth()->user()->name }}</h4>
                        <p class="text-white opacity-75 mb-2">{{ $ficha }}</p>
                        
                        <div class="d-inline-block px-3 py-1 rounded-pill" style="background: rgba(16,185,129,0.1); border: 1px solid var(--green-500);">
                            <span class="small-muted">Jornada:</span> 
                            <span class="text-green fw-bold">{{ $jornada }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="small">
                            <i class="bi bi-info-circle me-1"></i> 
                            Presenta este código al vigilante para registrar tu entrada o salida.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .text-green { color: #10b981 !important; }
    .neon { text-shadow: 0 0 10px rgba(16,185,129,0.5); }
    .qr-wrapper svg {
        display: block;
    }
</style>
@endsection