<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class TechnicianCheckedInNotification extends Notification
{
    use Queueable;

    protected $techId;
    protected $timestamp;
    protected $latitude;
    protected $longitude;

    /**
     * Crea una nueva notificación.
     *
     * @param  int  $techId
     * @param  string  $timestamp
     * @param  float  $latitude
     * @param  float  $longitude
     */
    public function __construct($techId, $timestamp, $latitude, $longitude)
    {
        $this->techId = $techId;
        $this->timestamp = $timestamp;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Define los canales de notificación.
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    /**
     * Convierte la notificación en un mensaje FCM.
     */
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'techId' => $this->techId,
                'timestamp' => $this->timestamp,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'type' => 'TechnicianCheckedIn'
            ])
            ->setNotification([
                'title' => 'Check-In de Técnico',
                'body' => 'El técnico ID ' . $this->techId . ' realizó el check-in a las ' . $this->timestamp,
            ]);
    }
}
