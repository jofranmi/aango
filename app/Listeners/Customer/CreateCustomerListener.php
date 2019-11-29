<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Notification\NotificationEvent;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreateCustomerListener
 * @package App\Listeners\Customer
 */
class CreateCustomerListener implements ShouldQueue
{
    /**
     * @var Customer $customer
     */
    protected $customer;

    /**
     * Create the event listener.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Handle the event.
     *
     * @param CreateCustomerEvent $event
     * @return void
     */
    public function handle(CreateCustomerEvent $event)
    {
        $customer = $this->customer
            ->create([
                'name' => $event->name,
                'address' => $event->address,
                'city' => $event->city,
                'state' => $event->state,
                'zip_code' => $event->zip_code,
                'phone' => empty($event->phone) ? 0000000 : $event->phone,
            ]);

        if (!$customer) {

            event(new NotificationEvent('There was an error creating the customer', 'alert-danger', $event->user));
        }

        event(new NotificationEvent('Customer has been created successfully', 'alert-success', $event->user));
    }

	/**
	 * @param CreateCustomerEvent $event
	 */
	public function failed(CreateCustomerEvent $event)
    {
        event(new NotificationEvent('There was an error creating the customer', 'alert-danger', $event->user));
    }
}
