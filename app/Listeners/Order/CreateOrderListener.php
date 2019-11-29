<?php

namespace App\Listeners\Order;

use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreateOrderListener
 * @package App\Listeners\Order
 */
class CreateOrderListener implements ShouldQueue
{
    /**
     * @var Order $order
     */
    protected $order;

    /**
     * Create the event listener.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
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
        $order = $event->user->orders()
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

        if (!$order) {

            event(new NotificationEvent('There was an error creating the order', 'alert-danger', $event->user));
        }

        event(new NotificationEvent('Order has been created successfully', 'alert-success', $event->user));
		event(new NotificationEvent('New order from ' . $event->user->customer->name, 'alert-success', null, 'private-office'));
    }

	/**
	 * @param CreateOrderEvent $event
	 */
	public function failed(CreateOrderEvent $event)
    {
        event(new NotificationEvent('There was an error creating the order', 'alert-danger', $event->user));
    }
}
