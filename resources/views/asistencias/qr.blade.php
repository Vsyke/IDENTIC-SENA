@extends('plantilla.app')

@section('contenido')
<div class="qr-container">

    <div class="qr-card">
        <h2 class="qr-title">Generar QR de Asistencia</h2>
        <p class="qr-subtitle">Ingrese el número de documento</p>

        @if(session('error'))
            <div class="qr-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('asistencias.qr.generar') }}" class="qr-form">
            @csrf
            <input 
                type="text" 
                name="documento" 
                placeholder="Número de documento"
                class="qr-input"
                required
            >
            <button type="submit" class="qr-button">
                Generar QR
            </button>
        </form>

        @if(isset($documento))
            <div class="qr-result">
                <div class="qr-box">
                    {!! QrCode::size(220)->generate(url('/asistencias/scan/'.$documento)) !!}
                </div>
                <span class="qr-doc">Documento: {{ $documento }}</span>
            </div>
        @endif
    </div>

</div>
@endsection
