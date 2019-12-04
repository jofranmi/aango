<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVehicle;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ItemController extends Controller
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

    public function __construct(Item $item, ItemVehicle $itemVehicle, Vehicle $vehicle)
	{
		$this->item = $item;
		$this->itemVehicle = $itemVehicle;
		$this->vehicle = $vehicle;
	}

	public function index()
	{
		$items = $this->itemVehicle
			->with('item')
			->get();
		$itemTypes = $this->item->all();
		$makes = $this->itemVehicle
			->select('make')
			->distinct()
			->orderBy('make', 'asc')
			->get();
		$makesVehicles = $this->vehicle
			->select('make')
			->distinct()
			->orderBy('make', 'asc')
			->get();

		return view('item.index')
			->with('items', $items)
			->with('itemTypes', $itemTypes)
			->with('makes', $makes)
			->with('makesVehicles', $makesVehicles);
	}
}
