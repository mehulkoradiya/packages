<?php
namespace Vendor\UserDiscounts\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code','type','value','active','starts_at','ends_at',
        'per_user_limit','max_uses','usage_count','stacking_priority','stackable','metadata'
    ];
    protected $casts = ['metadata'=>'array','starts_at'=>'datetime','ends_at'=>'datetime'];
    
    public function userDiscounts()
    {
        return $this->hasMany(UserDiscount::class, 'discount_id');
    }
}
