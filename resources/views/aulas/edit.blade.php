@extends('plantilla.app')

@section('titulo','Editar Aula')

@section('contenido')
<div class="container py-3">
  <div class="page-header">
    <h3 class="neon">Editar Aula</h3>
    <a href="{{ route('aulas.index') }}" class="btn btn-outline-success">Volver</a>
  </div>

  <div class="card p-3">
    <form action="{{ route('aulas.update', $aula) }}" method="POST">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">Código</label>
        <input name="codigo" value="{{ old('codigo', $aula->codigo) }}" class="form-control" required>
        @error('codigo') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input name="nombre" value="{{ old('nombre', $aula->nombre) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Capacidad</label>
        <input name="capacidad" type="number" value="{{ old('capacidad', $aula->capacidad) }}" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Ubicación</label>
        <input name="ubicacion" value="{{ old('ubicacion', $aula->ubicacion) }}" class="form-control">
      </div>

      <button class="btn btn-success">Actualizar</button>
    </form>
  </div>
</div>
@endsection
