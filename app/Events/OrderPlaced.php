<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class OrderPlaced
{
    use SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
}
