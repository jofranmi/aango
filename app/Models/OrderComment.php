<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderComment
 * @package App\Models
 */
class OrderComment extends Model
{
	/**
	 * @var array $fillable
	 */
	protected $fillable = [
		'order_id',
		'user_id',
		'comment',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'updated_at',
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
