<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserType
 * @package App\Models
 */
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

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOffice($query)
    {
        return $query->where('id', self::OFFICE);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCustomer($query)
    {
        return $query->where('id', self::CUSTOMER);
    }
}
