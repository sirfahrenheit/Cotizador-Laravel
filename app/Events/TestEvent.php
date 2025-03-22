<?php

namespace App\Events;

class TestEvent
{
    /**
     * El mensaje que se enviará con el evento.
     *
     * @var string
     */
    public $message;

    /**
     * Crea una nueva instancia del evento.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
