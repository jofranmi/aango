<?php

namespace App\Listeners\Order;

use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CreateOrderListener implements ShouldQueue
{
    /**
     * @var Auth $auth
     */
    protected $auth;

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * Create the event listener.
     *
     * @param Auth $auth
     * @param Order $order
     */
    public function __construct(Auth $auth, Order $order)
    {
        $this->auth = $auth;
        $this->order = $order;
    }

    /**
     * Handle the event.
     *
     * @param  CreateOrderEvent  $event
     * @return void
     */
    public function handle(CreateOrderEvent $event)
    {
        $order = $event->user->order()
            ->create([
                'customer_id' => $event->customerId ?: $event->user->customer_id,
                'status_id' => 1,
                'vin' => $event->vin,
                'year' => $event->vehicle['year'],
                'make' => $event->vehicle['make'],
                'model' => $event->vehicle['model'],
                'total' => $event->key['price'],
                'total_original' => $event->key['price'],
            ]);

        $order->items()
            ->create([
                'item_vehicle_id' => $event->key['id'],
                'price' => $event->key['price'],
                'price_original' => $event->key['price'],
            ]);

        if ($order) {
            event(new NotificationEvent('Order has been created successfully!', 'alert-success'));
        }
        else {
            event(new NotificationEvent('There was an error creating the order', 'alert-danger'));
        }
    }
}
