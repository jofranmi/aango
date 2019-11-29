<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVehicle;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;

class ItemController extends Controller
{
	/**
	 * @var DatabaseManager $db
	 */
	protected $db;
	/**
	 * @var Item $item
	 */
	protected $item;

	/**
	 * @var ItemVehicle $itemVehicle
	 */
	protected $itemVehicle;

    public function __construct(DatabaseManager $db, Item $item, ItemVehicle $itemVehicle)
	{
		$this->db = $db;
		$this->item = $item;
		$this->itemVehicle = $itemVehicle;
	}

	public function index()
	{
		$items = $this->itemVehicle
			->with('item')
			->get();
		$itemTypes = $this->item->all();
		$makes = $this->db->table('item_vehicles')
			->select('make')
			->distinct()
			->get();

		return view('item.index')
			->with('items', $items)
			->with('itemTypes', $itemTypes)
			->with('makes', $makes);
	}
}
