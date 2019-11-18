<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'item_vehicle_id',
        'price',
        'price_original',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function key()
    {
        return $this->belongsTo(ItemVehicle::class, 'item_vehicle_id');
    }

    public function item()
    {
        return $this->hasOneThrough(Item::class, ItemVehicle::class, 'id', 'id', 'item_vehicle_id', 'item_id');
    }
}
