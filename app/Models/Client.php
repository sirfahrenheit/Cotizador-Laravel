<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'cliente_id';

    protected $fillable = [
        'nombre',
        'direccion',  // Agrega la dirección aquí
        'telefono',
        'correo',
        // Otros campos...
    ];

 public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'cliente_id');
    }

    // Relaciones y otros métodos...
}


    /**
     * Relación: Un cliente puede tener muchas cotizaciones.
     */
   

