<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public $timestamps = false;
    public const ADMIN = 1;
    public const OFFICE = 2;
    public const CUSTOMER = 3;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
