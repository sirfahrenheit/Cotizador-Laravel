<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class WorkOrderCreatedNotification extends Notification implements ShouldQueue
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
        $title = 'Nueva Orden de Trabajo';
        $body  = 'Se ha creado la orden #' . $this->order->orden_id;
        $iconUrl = 'https://distribuidorajadi.site/images/mi-logo.png';
        $imageUrl = 'https://foo.bar/pizza-monster.png'; // Reemplaza con la URL de imagen deseada

        return FcmMessage::create()
            ->notification(
                FcmNotification::create(
                    $title,
                    $body,
                    null, // Puedes agregar la URL de una imagen aquí si lo deseas
                    [
                        'icon' => $iconUrl
                    ]
                )
            )
            ->data([
                'orden_id' => (string) $this->order->orden_id,
                'type'     => 'WorkOrderCreated',
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
