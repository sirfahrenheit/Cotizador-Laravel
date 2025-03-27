<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aquí se registran todos los canales de transmisión para tu aplicación.
| Los callbacks de autorización se utilizan para determinar si el usuario
| autenticado tiene permiso para escuchar el canal.
|
*/

/**
 * Canal para el check-in de técnicos.
 * Puedes ajustar la lógica de autorización según tus necesidades.
 */
Broadcast::channel('tech-checkin', function ($user) {
    // Por ejemplo, se autoriza si el usuario está autenticado.
    return (bool) $user;
});

/**
 * Canal para work orders (ordenes de trabajo).
 */
Broadcast::channel('work-orders', function ($user) {
    // Autoriza a cualquier usuario autenticado.
    return (bool) $user;
});
