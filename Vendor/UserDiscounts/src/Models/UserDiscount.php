<?php
namespace Vendor\UserDiscounts\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
	protected $fillable = [
		'user_id','discount_id','usage_count','usage_limit','assigned_at','revoked_at'
	];
	protected $casts = [
		'assigned_at'=>'datetime',
		'revoked_at'=>'datetime',
	];
}
