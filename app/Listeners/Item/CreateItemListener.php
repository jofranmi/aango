<?php

namespace App\Listeners\Item;

use App\Events\Item\CreateItemEvent;
use App\Events\Notification\NotificationEvent;
use App\Models\Item;
use App\Models\ItemVehicle;
use App\Models\Vehicle;
use App\Services\VIN\VINService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreateItemListener
 * @package App\Listeners\Item
 */
class CreateItemListener implements ShouldQueue
{
	/**
	 * @var Item $item
	 */
	protected $item;

	/**
	 * @var ItemVehicle $itemVehicle
	 */
	protected $itemVehicle;

	/**
	 * @var Vehicle $vehicle
	 */
	protected $vehicle;

	/**
	 * @var VINService $vinService
	 */
	protected $vinService;

	/**
	 * Create the event listener.
	 *
	 * @param Item $item
	 * @param ItemVehicle $itemVehicle
	 * @param Vehicle $vehicle
	 * @param VINService $vinService
	 */
    public function __construct(Item $item, ItemVehicle $itemVehicle, Vehicle $vehicle, VINService $vinService)
    {
    	$this->item = $item;
    	$this->itemVehicle = $itemVehicle;
    	$this->vehicle = $vehicle;
    	$this->vinService = $vinService;
    }

	/**
	 * Handle the event.
	 *
	 * @param CreateItemEvent $event
	 * @return void
	 */
    public function handle(CreateItemEvent $event)
    {
    	$item = $this->item->find($event->type);

    	if (empty($item)) {
    		return;
		}

		$key = $item->keys()->create([
				'year_from' => $event->yearFrom,
				'year_to' => $event->yearTo,
				'make' => strtoupper($event->make),
				'model' => $event->model,
				'price' => $event->price,
			]);

    	if(!$key) {
			event(new NotificationEvent('There was an error creating the key', 'alert-danger', $event->user));

			return true;
		}

		event(new NotificationEvent('Key has been created successfully', 'alert-success', $event->user));
    }

	public function failed(CreateItemEvent $event)
	{
		event(new NotificationEvent('There was an error creating the key', 'alert-danger', $event->user));
	}
}
