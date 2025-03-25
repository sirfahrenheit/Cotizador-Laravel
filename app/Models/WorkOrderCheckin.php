<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderCheckin extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tecnico_id',
        'latitude',
        'longitude',
        'checked_in_at'
    ];
}

