@extends('plantilla.app')

@section('titulo','Crear Ficha')

@section('contenido')
<div class="container py-3">
  <div class="page-header">
    <h3 class="neon">Crear Ficha</h3>
    <a href="{{ route('fichas.index') }}" class="btn btn-outline-success">Volver</a>
  </div>

  <div class="card p-3">
    <form action="{{ route('fichas.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Código</label>
        <input name="codigo" value="{{ old('codigo') }}" class="form-control" required>
        @error('codigo') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Programa</label>
        <input name="programa" value="{{ old('programa') }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Cantidad de aprendices</label>
        <input name="cantidad_estudiantes" type="number" value="{{ old('cantidad_estudiantes',0) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Aula</label>
        <select name="aula_id" class="form-control">
          <option value="">-- Sin asignar --</option>
          @foreach($aulas as $a)
            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
          @endforeach
        </select>
      </div>

      <button class="btn btn-success">Guardar</button>
    </form>
  </div>
</div>
@endsection
