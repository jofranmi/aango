<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * @package App\Models
 */
class Status extends Model
{
    const PENDING = 1;
    const PROCESSING = 2;
    const ASSIGNED = 3;
    const SHIPPED = 4;
    const COMPLETED = 5;
    const CANCELLED = 6;

    public function orders()
    {
        return $this->hasMany(Status::class);
    }
}
