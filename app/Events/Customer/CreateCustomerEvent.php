<?php

namespace App\Events\Customer;

use App\Events\Notification\NotificationEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

/**
 * Class CreateCustomerEvent
 * @package App\Events\Customer
 */
class CreateCustomerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $address
     */
    public $address;

    /**
     * @var string city
     */
    public $city;

    /**
     * @var string $state
     */
    public $state;

    /**
     * @var int $zip_code
     */
    public $zip_code;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->name = $request->name;
        $this->address = $request->address;
        $this->city = $request->city;
        $this->state = $request->state;
        $this->zip_code = $request->zip_code;
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
