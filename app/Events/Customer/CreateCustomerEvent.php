<?php

namespace App\Events\Customer;

use App\Events\AbstractEvent;
use Illuminate\Http\Request;

/**
 * Class CreateCustomerEvent
 * @package App\Events\Customer
 */
class CreateCustomerEvent extends AbstractEvent
{
    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $address
     */
    public $address;

    /**
     * @var string $city
     */
    public $city;

    /**
     * @var string $state
     */
    public $state;

    /**
     * @var int $zip_code
     */
    public $zip_code;

    /**
     * @var int $phone
     */
    public $phone;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->name = $request->name;
        $this->address = $request->address;
        $this->city = $request->city;
        $this->state = $request->state;
        $this->zip_code = $request->zip_code;
        $this->phone = $request->phone;
    }
}
