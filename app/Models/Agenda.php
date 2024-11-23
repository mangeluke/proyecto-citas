<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $table = 'agenda';

    protected $fillable=[
        'nombres', 
        'correo',
        'telefono',
        'tiposervicio',
        'fecha',
        'empleado_id',
    ];
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}

