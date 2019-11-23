<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Notification\NotificationEvent;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        Log::info(json_encode($event));
        Log::info('testzz');
        $customer = $this->customer
            ->create([
                'name' => $event->name,
                'address' => $event->address,
                'city' => $event->city,
                'state' => $event->state,
                'zip_code' => $event->zip_code,
            ]);

        if ($customer) {
            event(new NotificationEvent('Customer has been created successfully!', 'alert-success'));
        } else {
            event(new NotificationEvent('There was an error creating the customer', 'alert-danger'));
        }
    }

    public function failed(CreateCustomerEvent $event)
    {
        event(new NotificationEvent('There was an error creating the customer', 'alert-danger'));
    }
}
