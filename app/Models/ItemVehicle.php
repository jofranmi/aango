<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVehicle extends Model
{
    public function itemsOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
