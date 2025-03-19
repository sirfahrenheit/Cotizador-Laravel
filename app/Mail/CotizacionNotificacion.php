<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;
    public $publicLink;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion, $publicLink)
    {
        $this->cotizacion = $cotizacion;
        $this->publicLink = $publicLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Tomamos el nombre del cliente y número de cotización
        $clienteNombre    = $this->cotizacion->client->nombre ?? 'Cliente';
        $numeroCotizacion = $this->cotizacion->cotizacion_numero;

        // Leemos las condiciones y notas (o garantías) desde la BD
        $paymentConditions = $this->cotizacion->payment_conditions ?? '';
        $additionalNotes   = $this->cotizacion->additional_notes ?? '';

        return $this->subject('Nueva Cotización: ' . $numeroCotizacion)
                    ->view('emails.cotizacion-notificacion')
                    ->with([
                        'clienteNombre'     => $clienteNombre,
                        'numeroCotizacion'  => $numeroCotizacion,
                        'linkPublico'       => $this->publicLink,
                        'paymentConditions' => $paymentConditions,
                        'additionalNotes'   => $additionalNotes,
                    ]);
    }
}
