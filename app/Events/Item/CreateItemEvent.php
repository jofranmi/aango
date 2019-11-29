<?php

namespace App\Events\Item;

use App\Events\AbstractEvent;
use Illuminate\Http\Request;

class CreateItemEvent extends AbstractEvent
{
	/**
	 * @var int $yearFrom
	 */
	public $yearFrom;

	/**
	 * @var int $yearTo
	 */
	public $yearTo;

	/**
	 * @var string $make
	 */
	public $make;

	/**
	 * @var string $model
	 */
	public $model;

	/**
	 * @var int $type
	 */
	public $type;

	/**
	 * @var $price
	 */
	public $price;

	/**
	 * Create a new event instance.
	 *
	 * @param Request $request
	 */
    public function __construct(Request $request)
    {
    	parent::__construct();

    	$this->yearFrom = $request->yearFrom;
    	$this->yearTo = $request->yearTo;
    	$this->make = $request->make;
    	$this->model = $request->model;
    	$this->type = $request->type;
    	$this->price = $request->price;
    }
}
