<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 * @package App\Models
 */
class Vehicle extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'year',
        'make',
        'model',
		'vin_sequence'
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
}
