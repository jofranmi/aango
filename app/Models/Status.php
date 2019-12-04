<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * @package App\Models
 */
class Status extends Model
{
    public function orders()
    {
        return $this->hasMany(Status::class);
    }
}
