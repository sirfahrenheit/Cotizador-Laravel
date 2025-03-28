<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    /**
     * Forzamos a Eloquent a usar la tabla "actividades" en lugar de "actividads".
     */
    protected $table = 'actividades';

    protected $fillable = [
        'cliente_id',
        'tipo',
        'fecha',
        'descripcion',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id', 'cliente_id');
    }
}
