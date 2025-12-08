<div class="modal fade" id="modalAula" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Aula</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formAula">
                    @csrf
                    <input type="hidden" id="id">

                    <div class="mb-3">
                        <label>Código</label>
                        <input type="text" id="codigo" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" id="nombre" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Capacidad</label>
                        <input type="number" id="capacidad" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Ubicación</label>
                        <input type="text" id="ubicacion" class="form-control">
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardar">Guardar</button>
            </div>

        </div>
    </div>
</div>
