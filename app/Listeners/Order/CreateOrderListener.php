<?php

namespace App\Listeners\Order;

use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Models\Order;
use App\Models\Status;
use App\Services\ServiceLocation\ServiceLocationService;
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
     * @var ServiceLocationService $serviceLocationService
     */
    protected $serviceLocationService;

    /**
     * Create the event listener.
     *
     * @param Order $order
     * @param ServiceLocationService $serviceLocationService
     */
    public function __construct(Order $order, ServiceLocationService $serviceLocationService)
    {
        $this->order = $order;
        $this->serviceLocationService = $serviceLocationService;
    }

    /**
     * Handle the event.
     *
     * @param CreateOrderEvent $event
     * @return bool
     */
    public function handle(CreateOrderEvent $event)
    {
        $serviceLocation = $this->serviceLocationService->getOrCreateServiceLocation($event->serviceLocation);

        if (!$serviceLocation) {
            event(new NotificationEvent('There was an error creating the order', 'alert-danger', $event->user));

            return true;
        }

        $order = $event->user->orders()
            ->create([
                'customer_id' => $event->customerId ?: $event->user->customer_id,
                'service_location_id' => $serviceLocation->id,
                'status_id' => Status::PENDING,
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

            return true;
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
