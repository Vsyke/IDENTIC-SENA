<h1>Bienvenido estudiante {{ auth()->user()->name }}</h1>

<form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-sm btn-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Salir</button>
      </form>