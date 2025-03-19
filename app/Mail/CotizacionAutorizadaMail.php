<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionAutorizadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $clienteNombre = $this->cotizacion->client->nombre ?? 'Cliente';
        $numeroCotizacion = $this->cotizacion->cotizacion_numero;

        return $this->subject('Cotización Autorizada: ' . $numeroCotizacion)
                    ->view('emails.cotizacion-autorizada')
                    ->with([
                        'clienteNombre'    => $clienteNombre,
                        'numeroCotizacion' => $numeroCotizacion,
                    ]);
    }
}
