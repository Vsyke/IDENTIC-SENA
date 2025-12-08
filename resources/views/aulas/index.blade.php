@extends('plantilla.app')

@section('titulo', 'Aulas')

@section('contenido')

<div class="card bg-dark text-white shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Aulas</h3>
        <a href="{{ route('aulas.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Crear Aula
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaAulas" class="table table-striped text-center table-dark">
                <thead class="table-success text-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Ubicación</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aulas as $aula)
                        <tr>
                            <td>{{ $aula->codigo }}</td>
                            <td>{{ $aula->nombre }}</td>
                            <td>{{ $aula->capacidad }}</td>
                            <td>{{ $aula->ubicacion }}</td>
                            <td>{{ $aula->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                <a href="{{ route('aulas.edit', $aula) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                <form action="{{ route('aulas.destroy', $aula) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay aulas registradas.</td>
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
        $('#tablaAulas').DataTable({
            "language": {
                "url": "{{ asset('datatables/spanish.json') }}"
            }
        });
    });
</script>
@endpush
