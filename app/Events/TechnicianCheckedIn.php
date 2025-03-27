<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TechnicianCheckedIn implements ShouldBroadcast
{
    use SerializesModels;

    public $techId;
    public $timestamp;
    public $latitude;
    public $longitude;

    /**
     * Create a new event instance.
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
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tech-checkin');
    }

    /**
     * Nombre del evento transmitido.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'TechnicianCheckedIn';
    }
}
