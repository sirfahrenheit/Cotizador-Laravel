<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class WorkOrderCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $orderId;

    /**
     * Create a new event instance.
     *
     * @param  int  $orderId
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('work-orders');
    }

    /**
     * Nombre del evento transmitido.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'WorkOrderCreated';
    }
}
