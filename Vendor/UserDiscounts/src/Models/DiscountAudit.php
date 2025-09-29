<?php
namespace Vendor\UserDiscounts\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountAudit extends Model
{
    protected $fillable = ['user_id','discount_id','action','context','amount_before','amount_after','amount_discounted'];
    protected $casts = ['context'=>'array'];
}
