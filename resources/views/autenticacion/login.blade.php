<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IdenticSena</title>

    {{-- CSS del login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="logoContainer">
        <img src="{{ asset('assets/img/identicSena.png') }}" alt="Logo Identic Sena" id="imageLogo">
    </div>

    <div class="formContainer">

        {{-- MENSAJE DE ERROR --}}
        @if(session('error'))
            <div class="alert alert-danger" style="color: red; text-align:center; margin-bottom:10px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORMULARIO --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- INPUT EMAIL --}}
            <div class="userContainer">
                <input type="email"
                       id="loginEmail"
                       name="email"
                       placeholder="Correo electrónico"
                       value="{{ old('email') }}"
                       required>
            </div>

            @error('email')
                <div style="color:red; font-size:13px; margin-top:5px;">{{ $message }}</div>
            @enderror

            <br>

            {{-- INPUT PASSWORD --}}
            <div class="passwordContainer">
                <input type="password"
                       id="loginPassword"
                       name="password"
                       placeholder="Contraseña"
                       required>
            </div>

            @error('password')
                <div style="color:red; font-size:13px; margin-top:5px;">{{ $message }}</div>
            @enderror

            {{-- FORGOT PASSWORD --}}
            <div class="forgotpaswordContainer">
                <svg viewBox="0 0 640 640" id="logoKey">
                    <path
                        d="M400 416C497.2 416 576 337.2 576 240C576 142.8 497.2 64 400 64C302.8 64 224 142.8 224 240C224 258.7 226.9 276.8 232.3 293.7L71 455C66.5 459.5 64 465.6 64 472L64 552C64 565.3 74.7 576 88 576L168 576C181.3 576 192 565.3 192 552L192 512L232 512C245.3 512 256 501.3 256 488L256 448L296 448C302.4 448 308.5 445.5 313 441L346.3 407.7C363.2 413.1 381.3 416 400 416zM440 160C462.1 160 480 177.9 480 200C480 222.1 462.1 240 440 240C417.9 240 400 222.1 400 200C400 177.9 417.9 160 440 160z" />
                </svg>
                <a href="#" class="forgotpassword">Olvidé mi contraseña</a>
            </div>

            <br>

            {{-- BOTÓN SUBMIT --}}
            <div class="submitContainer">
                <input type="submit" value="INGRESAR" id="submitButton">
            </div>

            {{-- SEPARADOR --}}
            <div id="orContainer">
                <p>_______________<span id="orConector">O</span>_______________</p>
            </div>

            {{-- FOOTER DEL FORM --}}
            <div class="formFooterContainer">

                <div class="visitor">
                    <p>¿Eres
                        <a href="#" id="visitor">Visitante</a><span>?</span>
                    </p>
                </div>

                <div class="noAcount">
                    <p>¿No tienes cuenta?</p>
                    <a href="/registro" id="registro">Regístrate</a>
                </div>

            </div>

        </form>
    </div>

</body>
</html>
