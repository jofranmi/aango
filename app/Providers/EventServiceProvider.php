<?php

namespace App\Providers;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Item\CreateItemEvent;
use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Events\User\CreateUserEvent;
use App\Listeners\Customer\CreateCustomerListener;
use App\Listeners\Item\CreateItemListener;
use App\Listeners\Notification\NotificationListener;
use App\Listeners\Order\CreateOrderListener;
use App\Listeners\User\CreateUserListener;
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
        NotificationEvent::class => [NotificationListener::class],
        CreateOrderEvent::class => [CreateOrderListener::class],
        CreateCustomerEvent::class => [CreateCustomerListener::class],
        CreateUserEvent::class => [CreateUserListener::class],
		CreateItemEvent::class => [CreateItemListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
