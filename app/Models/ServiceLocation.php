<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceLocation
 * @package App\Models
 */
class ServiceLocation extends Model
{
    /**
     * @var array $fillable
     */
    public $fillable = [
        'name',
        'address',
        'address2',
        'city',
        'state',
        'zip_code',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
