<div class="modal fade" id="modalUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formUpdate" method="post">
                @csrf
                <input type="hidden" id="method_field" name="_method">
                
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modalTitle">Nuevo registro</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ingrese nombre completo" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control">
                                <small>Dejar vacío para mantener la actual</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="activo" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="activo" id="activo" class="form-select" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-lg-12 d-none" id="contenedor-ficha">
                            <div class="form-group mb-3">
                                <label for="ficha_id" class="form-label">Asignar Ficha <span class="text-danger">*</span></label>
                                <select name="ficha_id" id="ficha_id" class="form-select">
                                    <option value="">Seleccione una ficha...</option>
                                </select>
                                <div class="invalid-feedback">Debe seleccionar una ficha para el estudiante.</div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <hr class="border-secondary opacity-25">
                            <div class="form-group mb-3">
                                <label class="form-label">Roles del Sistema</label>
                                <div id="checkbox-roles" class="row p-2">
                                    </div>
                                <div class="invalid-feedback d-block" id="roles-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary shadow">
                        <i class="bi bi-save"> Guardar Cambios</i> 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>