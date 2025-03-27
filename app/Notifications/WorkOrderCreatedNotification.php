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
        return FcmMessage::create()
            ->notification(
                // Parámetros en orden:
                // 1) title, 2) body, 3) image, 4) array con atributos extra
                FcmNotification::create(
                    'Nueva Orden de Trabajo',
                    'Se ha creado la orden #' . $this->order->orden_id,
                    null, // Aquí podrías poner la URL de una imagen si quieres usar "image"
                    [
                        'icon' => 'https://distribuidorajadi.site/images/mi-logo.png'
                        // Puedes agregar otras propiedades extra:
                        // 'click_action' => 'https://...'
                    ]
                )
            )
            ->data([
                'orden_id' => (string) $this->order->orden_id,
                'type'     => 'WorkOrderCreated',
            ]);
    }
}

