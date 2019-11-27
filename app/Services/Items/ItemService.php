<?php

namespace App\Services\Items;

use App\Models\ItemVehicle;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ItemService
{
    /**
     * @var ItemVehicle $itemVehicle
     */
    protected $itemVehicle;

    public function __construct(ItemVehicle $itemVehicle)
    {
        $this->itemVehicle = $itemVehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return ItemVehicle|null
     */
    public function getItemForVehicle(Vehicle $vehicle): ?ItemVehicle
    {
        return $this->itemVehicle
            ->select(
                'id',
                'item_id',
                'price'
            )
            ->where('make', $vehicle->make)
            ->where('model', $vehicle->model)
            ->whereRaw('? between `year_from` and `year_to`', [$vehicle->year])
            ->with('item:id,name')
            ->first();
    }
}
