<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Asegúrate de incluir el campo 'role' en fillable si lo usas
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // agrega 'role' si la tabla tiene este campo
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
     * Obtiene un usuario por su correo electrónico.
     *
     * @param string $email
     * @return User|null
     */
    public static function getUserByEmail($email)
    {
        return static::where('email', $email)->first();
    }
}
