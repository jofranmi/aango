<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AbstractEvent
{
	use Dispatchable;
	use InteractsWithSockets;
	use SerializesModels;

	/**
     * @var User $user
     */
    public $user;

    /**
     * Create a new event instance.
     *
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }
}
