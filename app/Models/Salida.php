<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;
    protected $table='salida';
    protected $primaryKey='idSalida';
    
    protected $fillable = [
        'idvehiculo',
        'fecha_entrada',
        'fecha_salida',
        'duracion',
        'cobro',
        'idusuario',
        'idEntrada',
        'estado',
    ];

    public function vehiculo()
    {
        return $this->hasOne(Vehiculo::class, 'idvehiculo', 'idvehiculo');
    }
}
