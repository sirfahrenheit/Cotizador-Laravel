<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FcmToken extends Model
{
    use HasFactory;

    protected $table = 'fcm_tokens';

    protected $fillable = [
        'user_id',
        'token',
    ];

    /**
     * Relación: cada token pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
