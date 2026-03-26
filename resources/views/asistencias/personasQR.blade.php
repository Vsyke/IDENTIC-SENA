@extends('plantilla.app') {{-- Asegúrate de que este nombre sea el de tu archivo de plantilla --}}

@section('contenido')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="card shadow-lg" style="background: rgba(3,7,12,0.45); border: 1px solid rgba(16,185,129,0.2);">
                <div class="card-body p-4 text-center">
                    <h3 class="neon mb-4">SISTEMA IDENTIC | ESCÁNER</h3>
                    
                    <div id="reader" class="mx-auto rounded-3" style="border: 2px solid var(--green-500); max-width: 400px; overflow: hidden;"></div>
                    
                    <div id="result-container" class="mt-4 d-none">
                        <div id="result-message" class="alert py-2 fw-bold" style="background: rgba(16,185,129,0.1); border: 1px solid var(--green-500); color: var(--green-400);"></div>
                    </div>

                    <div class="mt-4">
                        <p class="small-muted">Bienvenido, <span class="text-green">{{ auth()->user()->name }}</span></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Estilo para los botones internos del lector */
    #reader button {
        background: linear-gradient(180deg, var(--green-400), var(--green-600)) !important;
        color: #03110b !important;
        border: none !important;
        padding: 8px 15px !important;
        border-radius: 4px !important;
        font-weight: bold !important;
        margin: 10px !important;
    }
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.pause();
        const resDiv = document.getElementById('result-container');
        const resMsg = document.getElementById('result-message');
        
        resDiv.classList.remove('d-none');
        resMsg.innerHTML = "PROCESANDO...";

        fetch("{{ route('asistencia.escanear') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ codigo_qr: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                resMsg.innerHTML = "✅ ACCESO REGISTRADO";
                setTimeout(() => window.location.reload(), 2000);
            } else {
                resMsg.innerHTML = "❌ ERROR: " + data.message;
                setTimeout(() => {
                    resDiv.classList.add('d-none');
                    html5QrcodeScanner.resume();
                }, 3000);
            }
        })
        .catch(err => {
            resMsg.innerHTML = "⚠️ FALLO DE SERVIDOR";
            setTimeout(() => html5QrcodeScanner.resume(), 3000);
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
        fps: 15, 
        qrbox: { width: 250, height: 250 } 
    });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection