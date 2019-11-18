<?php

namespace App\Listeners\Notification;

use App\Events\Notification\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @param NotificationEvent $event
     * @return bool
     */
    public function handle(NotificationEvent $event)
    {
        return true;
    }
}
