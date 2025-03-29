<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class TechnicianCheckedInNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $techId;
    protected $timestamp;
    protected $latitude;
    protected $longitude;

    /**
     * Crea una nueva notificación.
     *
     * @param  int    $techId
     * @param  string $timestamp
     * @param  float  $latitude
     * @param  float  $longitude
     */
    public function __construct($techId, $timestamp, $latitude, $longitude)
    {
        $this->techId    = $techId;
        $this->timestamp = $timestamp;
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Define los canales de notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    /**
     * Convierte la notificación en un mensaje FCM.
     *
     * @param  mixed  $notifiable
     * @return \NotificationChannels\Fcm\FcmMessage
     */
    public function toFcm($notifiable)
    {
        $title = 'Check-In de Técnico';
        $body  = 'El técnico ID ' . $this->techId . ' realizó el check-in a las ' . $this->timestamp;
        $iconUrl = 'https://distribuidorajadi.site/images/logo-notif.png';

        return FcmMessage::create()
            ->notification(
                FcmNotification::create(
                    $title,
                    $body,
                    null, // Puedes incluir la URL de una imagen en este parámetro si lo deseas
                    [
                        'icon' => $iconUrl
                    ]
                )
            )
            ->data([
                'techId'    => (string)$this->techId,
                'timestamp' => (string)$this->timestamp,
                'latitude'  => (string)$this->latitude,
                'longitude' => (string)$this->longitude,
                'type'      => 'TechnicianCheckedIn'
            ]);
    }
}
