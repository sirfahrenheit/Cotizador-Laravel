<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class QuoteTrackingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $actividad;

    public function __construct($actividad)
    {
        $this->actividad = $actividad;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        // Convertir la fecha, que viene como cadena, a un objeto Carbon para formatearla
        $formattedTime = Carbon::parse($this->actividad->fecha)->format('H:i');

        return FcmMessage::create()
            ->notification(
                FcmNotification::create(
                    'Seguimiento de Cotización Programado',
                    'Se ha registrado el seguimiento para la cotización #' . $this->actividad->id . ' a las ' . $formattedTime,
                    null, // Si deseas incluir una imagen, coloca la URL aquí
                    [
                        'icon' => 'https://distribuidorajadi.site/images/mi-logo.png'
                    ]
                )
            )
            ->data([
                'actividad_id' => (string) $this->actividad->id,
                'type'         => 'QuoteTracking'
            ]);
    }
}
