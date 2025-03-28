<?php

namespace App\Mail;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActividadNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $actividad;

    /**
     * Crea una nueva instancia de ActividadNotificacion.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return void
     */
    public function __construct(Actividad $actividad)
    {
        $this->actividad = $actividad;
    }

    /**
     * Construye el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva Actividad Registrada')
                    ->view('emails.actividad_notificacion');
    }
}
