@extends('plantilla.app')

@section('titulo', 'Fichas')

@section('contenido')

<div class="card bg-dark text-white shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Fichas</h3>
        <a href="{{ route('fichas.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Crear Ficha
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaFichas" class="table table-striped text-center table-dark">
                <thead class="table-success text-dark">
                    <tr>
                        <th>Código</th>
                        <th>Programa</th>
                        <th>Cantidad Estudiantes</th>
                        <th>Aula</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fichas as $ficha)
                        <tr>
                            <td>{{ $ficha->codigo }}</td>
                            <td>{{ $ficha->programa }}</td>
                            <td>{{ $ficha->cantidad_estudiantes }}</td>
                            <td>{{ $ficha->aula?->nombre ?? 'Sin asignar' }}</td>
                            <td>
                                <a href="{{ route('fichas.edit', $ficha) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                <form action="{{ route('fichas.destroy', $ficha) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay fichas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#tablaFichas').DataTable({
            "language": {
                "url": "{{ asset('datatables/spanish.json') }}"
            }
        });
    });
</script>
@endpush
