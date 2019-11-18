<?php

namespace App\Events\Order;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var $customerId
     */
    public $customerId;

    /**
     * @var $key
     */
    public $key;

    /**
     * @var User $user
     */
    public $user;

    /**
     * @var $vehicle
     */
    public $vehicle;

    /**
     * @var $vin
     */
    public $vin;

    /**
     * Create a new event instance.
     *
     * @param $customerId
     * @param $key
     * @param $vehicle
     * @param $vin
     * @param User $user
     */
    public function __construct($customerId, $key, $vehicle, $vin,  User $user)
    {
        $this->customerId = $customerId;
        $this->key = $key;
        $this->user = $user;
        $this->vehicle = $vehicle;
        $this->vin = $vin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notifications');
    }
}
