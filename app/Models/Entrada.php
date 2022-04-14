<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;
    protected $table='entrada';
    protected $primaryKey='idEntrada';
    
    protected $fillable = [
        'nombre',
        'celular',
        'idVehiculo',
        'idusuario',
        'fecha_hora',
        'estado',
    ];

    public function vehiculo()
    {
        return $this->hasOne(Vehiculo::class, 'idvehiculo', 'idvehiculo');
    }
}
