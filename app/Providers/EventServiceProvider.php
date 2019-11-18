<?php

namespace App\Providers;

use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Listeners\Notification\NotificationListener;
use App\Listeners\Order\CreateOrderListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        CreateOrderEvent::class => [CreateOrderListener::class],
        NotificationEvent::class => [NotificationListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
