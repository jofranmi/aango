<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemOrder
 * @package App\Models
 */
class ItemOrder extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'order_id',
        'item_vehicle_id',
        'price',
        'price_original',
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
