<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemVehicle
 * @package App\Models
 */
class ItemVehicle extends Model
{
	/**
	 * @var array $fillable
	 */
	protected $fillable = [
		'year_from',
		'year_to',
		'make',
		'model',
		'price',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at',
		'updated_at',
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
