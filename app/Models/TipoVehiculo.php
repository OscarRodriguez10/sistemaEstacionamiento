<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;
    protected $table='tipovehiculo';
    protected $primaryKey='idTipoVehiculo';
    
    protected $fillable = [
        'descripcion',
        'costo',
        'estado'
    ];
}
