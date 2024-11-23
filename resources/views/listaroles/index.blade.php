@role('admin')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listado Roles') }}
        </h2>
        <!-- Formulario para crear un nuevo rol -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <form action="{{ route('roles.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <!-- Botón que activa el contenedor de creación -->
                <h2>Crear Rol</h2>
                
                <!-- Contenedor oculto para crear el rol (con clase de recuadro central) -->
                <div id="crearRol" class="edit-form-container">
                    <label for="role_name" class="mt-3">Nombre del Rol</label>
                    <br>
                    <input type="text" name="role_name" class="form-control underline-input" id="role_name" required>
                    <br>
                    <br>
                    <hr>
                    <button type="submit" class="btn btn-primaryo mt-3">Asignar</button>
                    <!-- Botón de Cerrar -->
                    <button type="button" class="btn btn-secondary mt-3" onclick="cerrarFormulario()">Cerrar</button>
                </div>
            </div>
        </form>

    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        

        <h2 class="mt-4">Lista de Roles</h2>
        <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td style="text-align: center;">{{ $role->id }}</td>
                        <td style="text-align: center;">{{ $role->name }}</td>
                        <td style="text-align: center;">
                            <!-- Botón para mostrar el formulario de edición -->
                            <button class="btn btn-warning" onclick="toggleEditForm('editForm{{ $role->id }}')">Editar</button>

                            <!-- Formulario para eliminar el rol -->
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Contenedor del formulario de edición -->
                    <div id="editForm{{ $role->id }}" class="edit-form-container" style="display: none;">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="role_name">Editar Nombre del Rol</label>
                                <br>
                                <br>
                                <input type="text" name="role_name" value="{{ $role->name }}" required class="form-control">
                            </div>
                            <br>
                            <hr/>
                        <div class="group-button">
                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <button type="button" class="btn btn-cancel" onclick="toggleEditForm('editForm{{ $role->id }}')">Cerrar</button>
                        </div>
                        </form>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>




    
<script>
    $(document).ready(function() {
        // Verificamos si la tabla ya ha sido inicializada para evitar el error
        if (!$.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        }
    });

    function mostrarFormulario() {
        document.getElementById("crearRol").style.display = "block";
    }

    function cerrarFormulario() {
        document.getElementById("crearRol").style.display = "none";
    }

    function toggleEditForm(formId) {
        var form = document.getElementById(formId);
        if (form.style.display === "none") {
            form.style.display = "block"; // Mostrar el contenedor
        } else {
            form.style.display = "none"; // Ocultar el contenedor
        }
    }
</script>

</x-app-layout>

@else
   <div class="alert alert-danger">
    <strong>Acceso denegado:</strong> No tienes permiso para acceder a esta seccion
   </div>
@endif
