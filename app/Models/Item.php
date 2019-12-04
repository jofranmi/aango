<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App\Models
 */
class Item extends Model
{
    public function keys()
    {
        return $this->hasMany(ItemVehicle::class);
    }
}
