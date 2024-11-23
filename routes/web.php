<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ListaRolesController;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chirps', function () {
    return view('chirps.index');
}) -> name('chirps.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/agendacita', [AgendaController::class, 'index'])->name('agendacita.index');
    Route::post('/agendacita', [AgendaController::class,'store'])->name('agendacita.store');
    Route::get('/agendacita', [AgendaController::class,'show'])->name('agendacita.index');
    Route::post('/agendacita/ocupadas', [AgendaController::class, 'getOccupiedDates'])->name('agendacita.ocupadas');
    Route::get('/citasagendadas', function(){
        return view('citasagendadas.index');
    })->name('citasagendadas.index');
    //rutas para listar las citas
    Route::get('/citasagendadas',[AgendaController::class,'mostrarLista'])->name('citasagendadas.index');
    //ruta para editar los datos
    Route::get('/editcita/{id}',[AgendaController::class,'mostrarEditCita'])->name('editcita.index');
    //ruta para actualizar los datos
    Route::put('/updatecita/{id}',[AgendaController::class,'update'])->name('updatecita.update');
    Route::get('/deletecita/{id}', [AgendaController::class,'destroy'])->name('deletecita.destroy');
    Route::get('/listaempleado', function(){
        return view('listaempleado.index');
    })->name('listaempleado.index');
    //Empleado
    Route::get('/empleado',[EmpleadoController::class,'index'])->name('empleado.index');
    Route::post('/empleado', [EmpleadoController::class,'store'])->name('empleado.store');
    Route::get('/listaempleado',[EmpleadoController::class,'show'])->name('listaempleado.index');
    Route::get('/editempleado/{id}',[EmpleadoController::class,'mostrarEdit'])->name('editempleado.index');
    Route::put('/updatempleado/{id}',[EmpleadoController::class,'update'])->name('updatempleado.update');
    Route::get('/deletempleado/{id}', [EmpleadoController::class,'destroy'])->name('deletempleado.destroy');






    // Ruta para mostrar la lista de roles
    Route::get('/listaroles', [ListaRolesController::class, 'showRoles'])->name('listaroles.index');
    
    // Ruta para mostrar la lista de permisos
    Route::get('/listapermiso', [ListaRolesController::class, 'index'])->name('listapermiso.index');
    
    // Asignar un rol a un usuario
    Route::post('/roles/assign', [ListaRolesController::class, 'assignRole'])->name('roles.assign');
    
    // Eliminar un rol de un usuario
    Route::post('/roles/remove', [ListaRolesController::class, 'removeRole'])->name('roles.remove');
    
    Route::put('/roles/{id}', [ListaRolesController::class, 'update'])->name('roles.update');
    
    // Almacenar un nuevo rol
    Route::post('/roles/store', [ListaRolesController::class, 'store'])->name('roles.store');
    
    // Eliminar un rol existente
    Route::delete('/roles/{id}', [ListaRolesController::class, 'destroy'])->name('roles.destroy');



});

require __DIR__.'/auth.php';
