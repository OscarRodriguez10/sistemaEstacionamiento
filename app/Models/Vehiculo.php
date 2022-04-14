<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $primaryKey='idvehiculo';
    protected $fillable = [
        'descripcion',
        'numeroPlaca',
        'idTipoVehiculo',
        'estado',
    ];

    public function tipo()
    {
        return $this->hasOne(TipoVehiculo::class, 'idTipoVehiculo', 'idTipoVehiculo');
    }

}
