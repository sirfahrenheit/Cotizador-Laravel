<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // conserva 'role' si la tabla tiene este campo
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Ejemplo de método auxiliar.
     */
    public static function getUserByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Relación 1:N con la tabla fcm_tokens (modelo FcmToken).
     */
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    /**
     * Sobrescribe la ruta de notificación para FCM.
     * Retorna un array con todos los tokens FCM del usuario.
     */
    public function routeNotificationForFcm($notification = null)
    {
        return $this->fcmTokens()->pluck('token')->toArray();
    }
}
