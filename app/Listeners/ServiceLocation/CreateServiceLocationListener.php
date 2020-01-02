<?php

namespace App\Listeners\ServiceLocation;

use App\Events\Notification\NotificationEvent;
use App\Events\ServiceLocation\CreateServiceLocationEvent;
use App\Models\ServiceLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreateServiceLocationListener
 * @package App\Listeners\ServiceLocation
 */
class CreateServiceLocationListener implements ShouldQueue
{
    /**
     * @var ServiceLocation $serviceLocation
     */
    protected $serviceLocation;

    /**
     * Create the event listener.
     *
     * @param ServiceLocation $serviceLocation
     */
    public function __construct(ServiceLocation $serviceLocation)
    {
        $this->serviceLocation = $serviceLocation;
    }

    /**
     * Handle the event.
     *
     * @param CreateServiceLocationEvent $event
     * @return bool
     */
    public function handle(CreateServiceLocationEvent $event)
    {
        $location = $this->serviceLocation
            ->create([
                'name' => $event->name,
                'address' => $event->address,
                'address2' => $event->address2,
                'city' => $event->city,
                'state' => $event->state,
                'zip_code' => $event->zip_code,
                'phone' => $event->phone,
            ]);

        if (!$location) {
            event(new NotificationEvent('There was an error creating the service location', 'alert-danger', $event->user));

            return true;
        }

        event(new NotificationEvent('Service location has been created successfully', 'alert-success', $event->user));
    }

	/**
	 * @param CreateCustomerEvent $event
	 */
	public function failed(CreateCustomerEvent $event)
    {
        event(new NotificationEvent('There was an error creating the service location', 'alert-danger', $event->user));
    }
}
