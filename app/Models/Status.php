<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    public function orders()
    {
        return $this->hasMany(Status::class);
    }
}
