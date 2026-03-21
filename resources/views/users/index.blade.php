@extends('plantilla.app')

@push('estilos')
<style>
    /* 1. Modal adaptado al tema oscuro/verde */
    .modal-content {
        color: #e6f9f0 !important;
        background: #0b1220 !important; /* Usando var(--card-bg) */
        border: 1px solid var(--green-500) !important;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
        border-radius: 12px;
    }

    .modal-header {
        border-bottom: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(16, 185, 129, 0.05);
    }

    .modal-title {
        color: var(--green-400) !important;
        font-weight: 700;
        text-shadow: 0 2px 10px rgba(0, 255, 178, 0.2);
    }

    /* 2. Etiquetas y Texto */
    .form-label {
        color: #bfffe6 !important; /* Color brand-text */
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #ff6b6b !important; /* Rojo suave para que resalte en oscuro */
    }

    /* 3. Inputs y Selects (Sobrescribiendo blancos) */
    .modal-content .form-control, 
    .modal-content .form-select {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        color: #eafff3 !important;
    }

    .modal-content .form-control:focus, 
    .modal-content .form-select:focus {
        border-color: var(--green-accent) !important;
        box-shadow: 0 0 10px rgba(0, 230, 118, 0.2) !important;
        outline: none;
    }

    /* 4. Contenedor de Ficha (Estudiante) */
    #contenedor-ficha {
        background: rgba(16, 185, 129, 0.08) !important;
        padding: 18px;
        border-radius: 8px;
        border-left: 4px solid var(--green-accent);
        margin-top: 15px;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    /* 5. Checkboxes de Roles */
    .form-check-label {
        color: #e6f9f0 !important;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--green-500) !important;
        border-color: var(--green-500) !important;
    }

    /* 6. Botones del Modal */
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%); /* Hace la X blanca */
    }

    .btn-secondary {
        background: rgba(148, 163, 184, 0.1) !important;
        border: 1px solid var(--muted) !important;
        color: var(--muted) !important;
    }

    .btn-primary {
        background: linear-gradient(180deg, var(--green-400), var(--green-600)) !important;
        border: none !important;
        color: #03110b !important;
        font-weight: 700;
    }

    /* Arreglo DataTables */
    .dataTables_wrapper .dataTables_filter input {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
</style>
@endpush

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex align-items-center bg-transparent border-bottom border-secondary">
                    <h3 class="card-title flex-grow-1 neon"><strong>Usuarios</strong></h3>
                    @can('users_create')
                    <button type="button" class="btn btn-success" id="btnCreate">
                        <i class="bi bi-plus-circle"></i> Nuevo Usuario
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="listadoTable" class="table table-striped table-hover table-sm w-100">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Activo</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@canany(['users_create', 'users_edit'])
    @include('users.action')
@endcanany
@endsection

@push('scripts')
<script>
class UserManager extends CrudManager {
    constructor() {
        super("{{ url('usuarios') }}");
        this.initializeDataTable();
        this.loadRoles();
        this.loadFichas(); 
        this.setupRoleListener(); 
    }

    loadFichas() {
        fetch('{{ route("fichas.select") }}')
            .then(response => response.json())
            .then(fichas => {
                const select = document.getElementById('ficha_id');
                if (!select) return;
                select.innerHTML = '<option value="">Seleccione una ficha...</option>';
                fichas.forEach(f => {
                    const option = document.createElement('option');
                    option.value = f.id;
                    option.textContent = `${f.codigo} - ${f.programa}`;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar fichas:', error));
    }

    setupRoleListener() {
        // Delegación de eventos para los checkboxes de roles
        document.getElementById('checkbox-roles').addEventListener('change', (e) => {
            if (e.target.name === 'roles[]') {
                this.toggleFichaSelector();
            }
        });
    }

    toggleFichaSelector() {
        const checkboxes = document.querySelectorAll('input[name="roles[]"]:checked');
        const rolesSeleccionados = Array.from(checkboxes).map(cb => cb.value.toLowerCase());
        const contenedor = document.getElementById('contenedor-ficha');
        const selectFicha = document.getElementById('ficha_id');

        if (!contenedor || !selectFicha) return;

        if (rolesSeleccionados.includes('estudiante')) {
            contenedor.classList.remove('d-none');
            selectFicha.setAttribute('required', 'required');
        } else {
            contenedor.classList.add('d-none');
            selectFicha.removeAttribute('required');
            selectFicha.value = '';
        }
    }

    loadRoles(marcados = []) {
        fetch('{{ route("roles.select") }}')
            .then(response => response.json())
            .then(permisos => {
                const container = document.getElementById('checkbox-roles');
                container.innerHTML = '';
                permisos.forEach(p => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-1';
                    const div = document.createElement('div');
                    div.className = 'form-check';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'form-check-input';
                    checkbox.name = 'roles[]';
                    checkbox.value = p.name;
                    checkbox.id = `perm_${p.id}`;
                    if (marcados.includes(p.name)) checkbox.checked = true;

                    const label = document.createElement('label');
                    label.className = 'form-check-label';
                    label.htmlFor = `perm_${p.id}`;
                    label.textContent = p.name;

                    div.appendChild(checkbox);
                    div.appendChild(label);
                    col.appendChild(div);
                    container.appendChild(col);
                });
                this.toggleFichaSelector();
            })
            .catch(error => console.error('Error al cargar roles:', error));
    }

    initializeDataTable() {
        this.tabla = $(this.elements.table).DataTable({
            processing: true,
            serverSide: true,
            ajax: { url: this.baseUrl, type: 'GET' },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false},
                { data: 'name', name: 'name'},
                { data: 'email', name: 'email'},
                { data: 'roles', name: 'roles' },
                { data: 'activo', name: 'activo' }
            ],
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
            responsive: true,
            order: [[1, 'asc']]
        });
    }

    async showEditModal(id) {
        try {
            const response = await this.fetchData(`${this.baseUrl}/${id}`);
            this.isEditing = true;
            this.resetForm();
            this.elements.modalTitle.textContent = 'Editar Usuario';
            this.elements.methodField.value = 'PUT';
            document.getElementById('name').value = response.name || '';
            document.getElementById('email').value = response.email || '';
            document.getElementById('activo').value = response.activo ? '1' : '0';

            const rolesMarcados = (response.roles || []).map(p => p.name);
            this.loadRoles(rolesMarcados);

            setTimeout(() => {
                if (response.estudiante && response.estudiante.ficha_id) {
                    const selectFicha = document.getElementById('ficha_id');
                    selectFicha.value = response.estudiante.ficha_id;
                }
                this.toggleFichaSelector();
            }, 150);

            this.form.action = `${this.baseUrl}/${id}`;
            this.modal.show();
        } catch (error) { console.error('Error:', error); }
    }
}

document.addEventListener('DOMContentLoaded', () => { new UserManager(); });
</script>
@endpush