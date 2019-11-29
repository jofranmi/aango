<?php

namespace App\Events\Order;

use App\Events\AbstractEvent;
use Illuminate\Http\Request;

class CreateOrderEvent extends AbstractEvent
{
    /**
     * @var $customerId
     */
    public $customerId;

    /**
     * @var $key
     */
    public $key;

    /**
     * @var $vehicle
     */
    public $vehicle;

    /**
     * @var $vin
     */
    public $vin;

	/**
	 * Create a new event instance.
	 *
	 * @param Request $request
	 */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->customerId = $request->customerId;
        $this->key = $request->key;
        $this->vehicle = $request->vehicle;
        $this->vin = $request->vin;
    }
}
