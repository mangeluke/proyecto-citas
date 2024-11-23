<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class ListaRolesController extends Controller
{
    // Método para mostrar la lista de usuarios y roles
    public function index()
    {
        $users = User::all(); // Obtiene todos los usuarios
        $roles = Role::all(); // Obtiene todos los roles disponibles
        return view('listapermiso.index', compact('users', 'roles')); // Envía los datos a la vista
    }
    

    // Método para mostrar la lista de roles
    public function showRoles()
    {
        $roles = Role::all(); // Obtiene todos los roles disponibles
        return view('listaroles.index', compact('roles')); // Envía los datos a la vista
    }

    // Método para asignar un rol a un usuario
    public function assignRole(Request $request)
    {
        $user = User::find($request->user_id); // Encuentra al usuario
        $role = Role::find($request->role_id); // Encuentra el rol seleccionado

        if ($user && $role) {
            $user->assignRole($role->name); // Asigna el rol al usuario
            return redirect()->route('listapermiso.index')->with('success', 'Rol asignado correctamente');
        }

        return redirect()->route('listapermiso.index')->with('error', 'Usuario o rol no encontrado');
    }


    // Método para eliminar el rol de un usuario
    public function removeRole(Request $request)
    {
        $user = User::find($request->user_id); // Encuentra al usuario
        if ($user) {
            $user->removeRole($user->getRoleNames()->first()); // Elimina el rol actual
            return redirect()->route('listapermiso.index')->with('success', 'Rol eliminado correctamente');
        }
        return redirect()->route('listapermiso.index')->with('error', 'Usuario no encontrado');
    }

    // Método para crear un nuevo rol
    public function store(Request $request)
    {
        // Valida la entrada
        $request->validate([
            'role_name' => 'required|unique:roles,name|max:255',
        ]);

        // Crea un nuevo rol
        Role::create(['name' => $request->role_name]);

        return redirect()->route('listaroles.index')->with('success', 'Rol creado correctamente');
    }

    // Método para actualizar un rol
public function update(Request $request, $id)
{
    $request->validate([
        'role_name' => 'required|unique:roles,name,'.$id.'|max:255',
    ]);

    $role = Role::findOrFail($id);
    $role->name = $request->role_name;
    $role->save();

    return redirect()->route('listaroles.index')->with('success', 'Rol actualizado correctamente');
}


    // Método para eliminar un rol
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('listaroles.index')->with('success', 'Rol eliminado correctamente');
    }
}