<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class WorkOrderUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        $title = 'Orden de Trabajo Actualizada';
        $body  = 'La orden #' . $this->order->orden_id . ' ha sido actualizada.';
        $iconUrl = 'https://distribuidorajadi.site/images/mi-logo.png';
        $imageUrl = 'https://foo.bar/pizza-monster.png'; // Reemplaza con la URL de imagen deseada

        return FcmMessage::create()
            ->notification(
                FcmNotification::create(
                    $title,
                    $body,
                    null, // Puedes agregar la URL de una imagen aquí si lo deseas
                    [
                        'icon' => $iconUrl,
                    ]
                )
            )
            ->data([
                'orden_id' => (string) $this->order->orden_id,
                'type'     => 'WorkOrderUpdated',
                // Convertir a JSON las estructuras anidadas para cumplir que los valores sean cadenas
                'android'  => json_encode([
                    'notification' => [
                        'image' => $imageUrl
                    ]
                ]),
                'apns'     => json_encode([
                    'payload' => [
                        'aps' => [
                            'mutable-content' => '1'
                        ]
                    ],
                    'fcm_options' => [
                        'image' => $imageUrl
                    ]
                ]),
                'webpush'  => json_encode([
                    'headers' => [
                        'image' => $imageUrl
                    ]
                ]),
            ]);
    }
}
