<?php

namespace App\Events\Notification;

use App\Events\AbstractEvent;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

/**
 * Class NotificationEvent
 * @package App\Events\Notification
 */
class NotificationEvent extends AbstractEvent implements ShouldBroadcast
{
    /**
     * @var array $notification
     */
    public $notification;

    /**
     * @var string $channel
     */
    public $channel;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param string $alert
     * @param string $channel
     * @param User|null $user
     */
    public function __construct(string $message, string $alert, User $user = null, string $channel = 'private-user-')
    {
        parent::__construct();
        $this->user = Auth::user();

        if ($user != null) {
            $this->user = $user;
        }

        $this->notification = [
            'message' => $message,
            'alert' => $alert
        ];
        $to = $channel != 'private-user-' ? $channel : $channel . $this->user->id;
        $this->channel = $to;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
