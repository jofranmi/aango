<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVehicle extends Model
{
	protected $fillable = [
		'year_from',
		'year_to',
		'make',
		'model',
		'price',
	];

	public function itemsOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
