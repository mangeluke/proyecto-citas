@role('admin')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listado Permisos') }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <div class="container mt-4">
        <h2>Asignación de Roles</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="example" class="table table-striped table-bordered margin-sides">
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Rol Actual</th>
                    <th>Asignar Nuevo Rol</th>
                    <th>Eliminar Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td>
                            <button type="button" class="btn btn-success mt-2" onclick="showAssignRole(this, '{{ $user->id }}')">Asignar</button>
                        </td>
                        <td>
                            <form action="{{ route('roles.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-danger mt-2">Eliminar Rol</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="assign-role-modal" class="assign-role-container" style="display: none;">
            <h3>Asignar Nuevo Rol</h3>
            <br>
            <form id="roleAssignForm" action="{{ route('roles.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="user_id">
                <select name="role_id" class="form-control">
                    <option value="">Sin rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <br>
                <br>
                <hr>
                <button type="submit" class="btn btn-success mt-2">Confirmar</button>
                <button type="button" class="btn btn-danger mt-2" onclick="hideAssignRole()">Cancelar</button>
            </form>
        </div>
    </div>
</x-app-layout>

@else
    <div class="alert alert-danger">
        <strong>Acceso denegado:</strong> No tienes permiso para acceder a esta sección.
    </div>
@endif

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>



<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    function showAssignRole(button, userId) {
        const modal = document.getElementById('assign-role-modal');
        modal.style.display = "block";
        document.getElementById('user_id').value = userId; // Establece el ID del usuario en el formulario
    }

    function hideAssignRole() {
        const modal = document.getElementById('assign-role-modal');
        modal.style.display = "none"; // Oculta el modal
    }
</script>
