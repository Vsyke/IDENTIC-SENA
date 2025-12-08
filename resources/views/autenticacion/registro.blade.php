<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <div class="registro-container">
        <h1 class="registro-titulo">Registro de Usuario</h1>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('estudiantes.registro') }}" method="POST">
            @csrf

            {{-- Tipo de documento --}}
            <div class="form-group">
                <label for="tipo_documento" class="form-label">Tipo de documento:</label>
                <select id="tipo_documento" name="tipo_documento" class="form-input" required>
                    <option value="">Tipo de documento</option>
                    <option value="cc">Cédula de ciudadanía</option>
                    <option value="ti">Tarjeta de identidad</option>
                    <option value="ce">Cédula de extranjería</option>
                    <option value="pp">Pasaporte</option>
                </select>
            </div>

            {{-- Número de documento --}}
            <div class="form-group">
                <label for="numero_documento" class="form-label">Número de documento:</label>
                <input type="number" id="numero_documento" name="numero_documento" class="form-input"
                    placeholder="Número de documento" required>
            </div>

            {{-- Primer nombre --}}
            <div class="form-group">
                <label for="primer_nombre" class="form-label">Primer nombre:</label>
                <input type="text" id="primer_nombre" name="primer_nombre" class="form-input"
                    placeholder="Primer nombre" required>
            </div>

            {{-- Segundo nombre --}}
            <div class="form-group">
                <label for="segundo_nombre" class="form-label">Segundo nombre:</label>
                <input type="text" id="segundo_nombre" name="segundo_nombre" class="form-input"
                    placeholder="Segundo nombre">
            </div>

            {{-- Primer apellido --}}
            <div class="form-group">
                <label for="primer_apellido" class="form-label">Primer apellido:</label>
                <input type="text" id="primer_apellido" name="primer_apellido" class="form-input"
                    placeholder="Primer apellido" required>
            </div>

            {{-- Segundo apellido --}}
            <div class="form-group">
                <label for="segundo_apellido" class="form-label">Segundo apellido:</label>
                <input type="text" id="segundo_apellido" name="segundo_apellido" class="form-input"
                    placeholder="Segundo apellido">
            </div>

            {{-- Correo --}}
            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Correo electrónico"
                    required>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Contraseña"
                    required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                    placeholder="Confirmar contraseña" required>
            </div>

            {{-- Teléfono --}}
            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-input" placeholder="Teléfono">
            </div>

            {{-- Botón --}}
            <div class="form-group">
                <input type="submit" value="Registrarse" class="form-submit">
            </div>

            <p class="form-footer">
                ¿Ya tienes una cuenta?
                <a href="/login" class="form-link">Inicia sesión</a>
            </p>

        </form>
    </div>
</body>

</html>