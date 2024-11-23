<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    use HasFactory;
        protected $fillable=[
            'identificacion', //del campo,del name del input
            'nombres',
            'apellidos',
            'correo',
            'direccion',
            'telefono',
            'tipocontrato',
            'datesemana',
        ];
        public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }
    
}
