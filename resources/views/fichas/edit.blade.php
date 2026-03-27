@extends('plantilla.app')

@section('titulo','Editar Ficha')

@section('contenido')
<div class="container py-3">
  <div class="page-header">
    <h3 class="neon">Editar Ficha</h3>
    <a href="{{ route('fichas.index') }}" class="btn btn-outline-success">Volver</a>
  </div>

  <div class="card p-3">
    <form action="{{ route('fichas.update', $ficha) }}" method="POST">
      @csrf @method('PUT')


    <div class="mb-3">
    <label class="text-green">Jornada de la Ficha</label>
    <select name="jornada" class="form-control">
        <option style="background-color: #0f1720;" value="Mañana" {{ $ficha->jornada == 'Mañana' ? 'selected' : '' }}>Mañana (06:00 - 12:00)</option>
        <option style="background-color: #0f1720;" value="Tarde" {{ $ficha->jornada == 'Tarde' ? 'selected' : '' }}>Tarde (12:00 - 18:00)</option>
        <option style="background-color: #0f1720;" value="Noche" {{ $ficha->jornada == 'Noche' ? 'selected' : '' }}>Noche (18:00 - 22:00)</option>
        <option style="background-color: #0f1720;" value="Madrugada" {{ $ficha->jornada == 'Madrugada' ? 'selected' : '' }}>Madrugada (22:00 - 06:00)</option>
    </select>
</div>

      <div class="mb-3">
        <label class="form-label">Código</label>
        <input name="codigo" value="{{ old('codigo', $ficha->codigo) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Programa</label>
        <input name="programa" value="{{ old('programa', $ficha->programa) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Cantidad de aprendices</label>
        <input name="cantidad_estudiantes" type="number" value="{{ old('cantidad_estudiantes', $ficha->cantidad_estudiantes) }}" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Aula</label>
        <select name="aula_id" class="form-control">
          <option style="background-color: #0f1720;" value="">-- Sin asignar --</option>
          @foreach($aulas as $a)
            <option style="background-color: #0f1720;" value="{{ $a->id }}" @if(old('aula_id', $ficha->aula_id)==$a->id) selected @endif>{{ $a->nombre }}</option>
          @endforeach
        </select>
      </div>

      <button class="btn btn-success">Actualizar</button>
    </form>
  </div>
</div>
@endsection
