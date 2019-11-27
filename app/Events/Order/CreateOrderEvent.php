<?php

namespace App\Events\Order;

use App\Events\AbstractEvent;

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
     * @param $customerId
     * @param $key
     * @param $vehicle
     * @param $vin
     */
    public function __construct($customerId, $key, $vehicle, $vin)
    {
        parent::__construct();

        $this->customerId = $customerId;
        $this->key = $key;
        $this->vehicle = $vehicle;
        $this->vin = $vin;
    }
}
