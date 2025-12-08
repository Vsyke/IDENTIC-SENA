



@extends('plantilla.app')
@push('estilos')

@endpush
@section('contenido')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Dashboard De Asistencias</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard Asistencias</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-6">
<div class="card mb-4" id="resumen-card">
    <div class="card-header">
        <h3 class="card-title">Resumen Diario de Asistencias (<span
                id="periodo-display">Mañana</span>)</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Rol de Personas</th>
                    <th>Asistieron / Total</th>
                    <th style="width: 40px">Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                {{-- 🚀 Bucle para iterar sobre todos los roles del Controller --}}
                @php $contador = 1; @endphp
                @foreach ($datosAsistencia as $rol => $datos)
                    <tr class="align-middle">
                        <td>{{ $contador++ }}.</td>
                        <td>{{ ucfirst($rol) }}</td>
                        <td>
                            <small class="text-muted">{{ $datos['asistieronHoy'] }} / {{ $datos['totalRol'] }}</small>
                            <div class="progress progress-xs">
                                <div class="progress-bar 
                                    @if ($rol == 'estudiante') text-bg-primary
                                    @elseif ($rol == 'maestro') text-bg-info
                                    @elseif ($rol == 'vigilante') text-bg-warning
                                    @else text-bg-secondary @endif"
                                    style="width: {{ $datos['porcentaje'] }}%">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if ($rol == 'estudiante') text-bg-primary
                                @elseif ($rol == 'maestro') text-bg-info
                                @elseif ($rol == 'vigilante') text-bg-warning
                                @else text-bg-secondary @endif">
                                {{ $datos['porcentaje'] }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
                {{-- 🚫 Fin del bucle --}}
            </tbody>
        </table>
    </div>
    {{-- Mantienes el footer tal cual lo tienes --}}
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item active"><a class="page-link periodo-link" href="#"
                    data-periodo="Mañana">1</a></li>
            <li class="page-item"><a class="page-link periodo-link" href="#"
                    data-periodo="Tarde">2</a></li>
            <li class="page-item"><a class="page-link periodo-link" href="#"
                    data-periodo="Noche">3</a></li>
            <li class="page-item"><a class="page-link periodo-link" href="#"
                    data-periodo="Madrugada">4</a></li>
            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </div>
</div>
                        <div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Registros de Ingreso Recientes</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>Persona (Rol)</th>
                    <th>Hora de Entrada</th>
                    <th style="width: 40px">Estado</th>
                </tr>
            </thead>
            <tbody>
                {{-- Iteramos sobre los registros que vienen del controlador --}}
                @forelse ($registrosRecientes as $registro)
                    <tr class="align-middle">
                        <td>{{ $registro->id }}.</td>
                        <td>
                            {{ $registro->user->name ?? 'Usuario Desconocido' }}
                            {{-- Mostrar el rol principal de la persona --}}
                            <small class="text-muted">({{ $registro->user->roles->first()->name ?? 'N/A' }})</small>
                        </td>
                        <td>
                            {{-- Formateamos la hora de entrada. Asume que 'entrada' es un Carbon instance --}}
                            {{ $registro->entrada->format('h:i A') }}
                        </td>
                        <td>
                            {{-- El estado será "Entrada" o "Completo" si también tiene salida --}}
                            @php
                                $estado = $registro->salida ? 'Completo' : 'En la sede';
                                $clase = $registro->salida ? 'text-bg-success' : 'text-bg-warning';
                            @endphp
                            <span class="badge {{ $clase }}">{{ $estado }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay registros de ingreso recientes hoy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

                    </div>
                    <div class="col-md-6">

                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Registros Totales del Día</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">ID</th>
                                            <th>Persona</th>
                                            <th>Entrada/Salida</th>
                                            <th style="width: 40px">Ficha/Programa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="align-middle">
                                            <td>1.</td>
                                            <td>Ana Díaz</td>
                                            <td>07:58 AM / N/A</td>
                                            <td><span class="badge text-bg-danger">2056321</span></td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td>2.</td>
                                            <td>Pedro López</td>
                                            <td>08:05 AM / 04:30 PM</td>
                                            <td><span class="badge text-bg-warning">2056322</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Equipos (Computadores) Registrados</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">ID</th>
                                            <th>Tipo</th>
                                            <th>Marca y Serie</th>
                                            <th style="width: 40px">Asociado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="align-middle">
                                            <td>1.</td>
                                            <td>Portátil</td>
                                            <td>HP / SN12345</td>
                                            <td><span class="badge text-bg-info">Instructor</span></td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td>2.</td>
                                            <td>Portatil</td>
                                            <td>Dell / SN67890</td>
                                            <td><span class="badge text-bg-warning">Estudiante</span></td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td>3.</td>
                                            <td>Portátil</td>
                                            <td>Lenovo / SN11223</td>
                                            <td><span class="badge text-bg-secondary">Visitante</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-bg-info elevation-1"><i class="bi bi-people"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total de Personas</span>
                                <span class="info-box-number">2,540</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-bg-success elevation-1"><i
                                    class="bi bi-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Presentes Hoy</span>
                                <span class="info-box-number">2,300</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-bg-danger elevation-1"><i class="bi bi-x-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Ausentes Hoy</span>
                                <span class="info-box-number">240</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-bg-warning elevation-1"><i
                                    class="bi bi-clock-history"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Llegadas Tarde</span>
                                <span class="info-box-number">55</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Obtener los elementos clave
                const periodoLinks = document.querySelectorAll('.periodo-link');
                const periodoDisplay = document.getElementById('periodo-display');

                // 2. Asignar el evento click a cada enlace de período
                periodoLinks.forEach(link => {
                    link.addEventListener('click', function (event) {
                        // Previene que la página salte o se recargue
                        event.preventDefault();

                        // Obtener el período del atributo 'data-periodo' del enlace clickeado
                        const nuevoPeriodo = this.getAttribute('data-periodo');

                        // 3. Cambiar el título
                        if (periodoDisplay) {
                            periodoDisplay.textContent = nuevoPeriodo;
                        }

                        // 4. (Opcional) Manejar la clase 'active' para resaltado visual
                        // Quitar 'active' de todos los hermanos (li)
                        periodoLinks.forEach(p => p.closest('.page-item').classList.remove('active'));
                        // Agregar 'active' al elemento padre (li) del enlace clickeado
                        this.closest('.page-item').classList.add('active');
                    });
                });
            });
        </script>
@endsection
    @push('scripts')
        <script>
            document.getElementById('itemDashboard').classList.add('active');
        </script>
    @endpush
</main>