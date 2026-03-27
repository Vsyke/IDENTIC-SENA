@extends('plantilla.app')

@push('estilos')
<style>
    .card-jornadas { border-top: 3px solid #10b981; }
    .text-green-neon { color: #10b981; font-weight: bold; }
</style>
@endpush

@section('contenido')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard De Asistencias</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            {{-- FILA PRINCIPAL --}}
            <div class="row">
                {{-- COLUMNA IZQUIERDA --}}
                <div class="col-md-6">
                    {{-- Resumen Diario --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Resumen Diario (<span>{{ $periodoSolicitado }}</span>)</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Rol</th>
                                        <th>Asistieron / Total</th>
                                        <th style="width: 40px"> % </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach ($datosAsistencia as $rol => $datos)
                                        <tr class="align-middle">
                                            <td>{{ $contador++ }}.</td>
                                            <td class="text-capitalize">{{ $rol }}</td>
                                            <td>
                                                <small class="text-muted">{{ $datos['asistieronHoy'] }} / {{ $datos['totalRol'] }}</small>
                                                <div class="progress progress-xs" style="height: 8px;"> {{-- Aseguramos una altura mínima --}}
    <div class="progress-bar 
        @if ($rol == 'estudiante') bg-primary 
        @elseif ($rol == 'maestro') bg-info 
        @elseif ($rol == 'vigilante') bg-warning 
        @else bg-secondary @endif"
        role="progressbar" 
        style="width: {{ $datos['porcentaje'] }}%;" {{-- El punto y coma es vital --}}
        aria-valuenow="{{ $datos['porcentaje'] }}" 
        aria-valuemin="0" 
        aria-valuemax="100">
    </div>
</div>
                                            </td>
                                            <td><span class="badge @if ($rol == 'estudiante') text-bg-primary @elseif ($rol == 'maestro') text-bg-info @elseif ($rol == 'vigilante') text-bg-warning @else text-bg-secondary @endif">{{ $datos['porcentaje'] }}%</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                @foreach(['Mañana', 'Tarde', 'Noche', 'Madrugada'] as $index => $p)
                                    <li class="page-item {{ $periodoSolicitado == $p ? 'active' : '' }}">
                                        <a class="page-link" href="{{ route('dashboard', ['periodo' => $p]) }}">{{ $index + 1 }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Registros Recientes --}}
                    <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Registros de Ingreso Recientes</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Persona (Rol)</th>
                                        <th>Entrada</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($registrosRecientes as $registro)
                                        <tr class="align-middle">
                                            <td>{{ $registro->user->name ?? 'N/A' }} <small class="text-muted">({{ $registro->user->roles->first()->name ?? 'N/A' }})</small></td>
                                            <td>{{ $registro->entrada->format('h:i A') }}</td>
                                            <td><span class="badge {{ $registro->salida ? 'text-bg-success' : 'text-bg-warning' }}">{{ $registro->salida ? 'Completo' : 'En sede' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center">Sin ingresos hoy.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- COLUMNA DERECHA --}}
                <div class="col-md-6">

                <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Registros Totales del Día</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Persona</th>
                                        <th>Entrada/Salida</th>
                                        <th>Ficha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($registrosTotales as $registro)
                                        <tr class="align-middle">
                                            <td>{{ $registro->id }}.</td>
                                            <td>{{ $registro->user->name ?? 'N/A' }}</td>
                                            <td>{{ $registro->entrada->format('h:i A') }} / {{ $registro->salida ? $registro->salida->format('h:i A') : 'N/A' }}</td>
                                            <td><span class="badge {{ $registro->salida ? 'text-bg-warning' : 'text-bg-danger' }}">{{ $registro->user->ficha ?? 'N/A' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center">No hay registros hoy.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- ESTADO DE JORNADAS --}}
                    <div class="card mb-4 card-jornadas">
                        <div class="card-header border-0"><h3 class="card-title text-green-neon">Estado de Jornadas</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-striped table-valign-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Jornada</th>
                                        <th>Total</th>
                                        <th>Hoy</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reporteJornadas as $fila)
                                    <tr>
                                        <td>{{ $fila->jornada ?? 'Sin Asignar' }}</td>
                                        <td>{{ $fila->total_estudiantes }}</td>
                                        <td><span class="badge {{ $fila->presentes_hoy > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $fila->presentes_hoy }}</span></td>
                                        <td><a href="{{ route('fichas.index') }}" class="btn btn-xs btn-outline-success"><i class="bi bi-pencil-square"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- EQUIPOS REGISTRADOS --}}
                    <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Equipos Registrados</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Marca/Serie</th>
                                        <th>Asociado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($equiposRegistrados as $equipo)
                                        @php
                                            $rolAsociado = $equipo->user->roles->first()->name ?? 'N/A';
                                            $claseRol = ($rolAsociado == 'estudiante') ? 'text-bg-warning' : (($rolAsociado == 'maestro') ? 'text-bg-info' : 'text-bg-primary');
                                        @endphp
                                        <tr class="align-middle">
                                            <td>{{ $equipo->tipo }}</td>
                                            <td>{{ $equipo->marca_serie }}</td>
                                            <td><span class="badge {{ $claseRol }}">{{ ucfirst($rolAsociado) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center">Sin equipos registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILA DE INFO BOXES --}}
            <div class="row mt-2">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon text-bg-info elevation-1"><i class="bi bi-people"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Personas</span>
                            <span class="info-box-number">{{ number_format($totalPersonas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon text-bg-success elevation-1"><i class="bi bi-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Presentes Hoy</span>
                            <span class="info-box-number">{{ number_format($presentesHoy, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon text-bg-danger elevation-1"><i class="bi bi-x-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Ausentes Hoy</span>
                            <span class="info-box-number">{{ number_format($ausentesHoy, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon text-bg-secondary elevation-1"><i class="bi bi-laptop"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Equipos en Sede</span>
                            <span class="info-box-number">{{ count($equiposRegistrados) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dashboardItem = document.getElementById('itemDashboard');
        if(dashboardItem) dashboardItem.classList.add('active');
    });
</script>
@endpush