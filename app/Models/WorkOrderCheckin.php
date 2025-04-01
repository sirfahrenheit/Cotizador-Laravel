<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderCheckin extends Model
{
    // Si tu tabla se llama "work_order_checkins", especifica el nombre
    protected $table = 'work_order_checkins';

    // Define los campos que se pueden asignar en masa
    protected $fillable = [
        'tecnico_id',
        'latitude',
        'longitude',
        'checked_in_at',
    ];

    /**
     * Relación con el técnico (usuario) que hizo el check-in.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
