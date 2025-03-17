<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'ordenes_de_trabajo';
    protected $primaryKey = 'orden_id';

    protected $fillable = [
        'fecha',
        'tareas',
        'avances',
        'solicitudes',
        'estado',
        'tecnico_id'
    ];

    // Relación con el técnico asignado (usuario)
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
