<?php
namespace Vendor\UserDiscounts\Services;

use Vendor\UserDiscounts\Models\Discount;
use Vendor\UserDiscounts\Models\UserDiscount;
use Vendor\UserDiscounts\Models\DiscountAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DiscountManager {
    protected array $config;

    public function __construct(array $config = null) {
        $this->config = $config;
    }

    public function assign(int $userId, int $discountId, ?int $usageLimit = null) {
        return DB::transaction(function () use ($userId, $discountId, $usageLimit) {
            $ud = UserDiscount::where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->lockForUpdate()->first();
            if (!$ud) {
                $ud = UserDiscount::create([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'usage_limit' => $usageLimit,
                    'assigned_at' => now(),
                ]);
            } else {
                $ud->update([
                    'usage_limit' => $usageLimit,
                    'assigned_at' => now(),
                ]);
            }
            event(new \Vendor\UserDiscounts\Events\DiscountAssigned($ud));
            DiscountAudit::create(['user_id'=>$userId,'discount_id'=>$discountId,'action'=>'assigned']);
            return $ud;
        });
    }

    public function revoke(int $userId, int $discountId) {
        $ud = UserDiscount::where(['user_id'=>$userId,'discount_id'=>$discountId])->first();
        if ($ud) {
            $ud->update(['revoked_at'=>now()]);
            event(new \Vendor\UserDiscounts\Events\DiscountRevoked($ud));
            DiscountAudit::create(['user_id'=>$userId,'discount_id'=>$discountId,'action'=>'revoked']);
        }
    }

    public function eligibleFor(int $userId, array $context = []): Collection {
        $now = Carbon::now();
        $discounts = Discount::where('active', true)
            ->where(function($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at','<=',$now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at','>=',$now);
            })
            ->with(['userDiscounts' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get();

        // exclude revoked or per-user ineligible
        return $discounts->filter(function($d) use ($userId) {
            $ud = $d->userDiscounts->first();
            if ($ud && $ud->revoked_at) return false;
            return true;
        })->sortBy(function($d){
            return [$d->stacking_priority, ($d->type === 'percentage' ? 0 : 1), $d->id];
        })->values();
    }

    public function apply(int $userId, float $amount, array $context = []) {
        $original = $this->round($amount);
        $remaining = $original;
        $applied = [];
        $eligible = $this->eligibleFor($userId, $context);

        return DB::transaction(function () use ($userId, $original, $remaining, $applied, $eligible, $context) {
            $totalPercentApplied = 0.0;
            foreach ($eligible as $d) {
                if (!$d->active) continue;

                // Lock user discount row
                $userDiscount = UserDiscount::where('user_id', $userId)
                    ->where('discount_id', $d->id)
                    ->lockForUpdate()->first();
                if (!$userDiscount) {
                    $userDiscount = UserDiscount::create([
                        'user_id' => $userId,
                        'discount_id' => $d->id,
                        'usage_limit' => null,
                        'usage_count' => 0,
                        'assigned_at' => now(),
                    ]);
                }

                $limit = $userDiscount->usage_limit ?? $d->per_user_limit;
                if (!is_null($limit) && $userDiscount->usage_count >= $limit) {
                    continue; // usage exhausted
                }

                // Lock discount row for global usage
                $discountRow = Discount::where('id', $d->id)->lockForUpdate()->first();
                if (!is_null($discountRow->max_uses) && $discountRow->usage_count >= $discountRow->max_uses) {
                    continue; // global usage exhausted
                }

                // compute discount amount
                if ($d->type === 'percentage') {
                    $percent = (float)$d->value;
                    $allowedPercent = $percent;
                    if (($totalPercentApplied + $percent) > $this->config['max_total_percentage']) {
                        $allowedPercent = max(0, $this->config['max_total_percentage'] - $totalPercentApplied);
                    }
                    $discountAmount = $this->round($remaining * ($allowedPercent / 100.0));
                    $totalPercentApplied += $allowedPercent;
                } else {
                    $discountAmount = min($remaining, $this->round((float)$d->value));
                }

                if ($discountAmount <= 0) {
                    continue;
                }

                // atomic increments
                $userDiscount->increment('usage_count');
                $discountRow->increment('usage_count');

                // subtract and record
                $remaining = max(0, $this->round($remaining - $discountAmount));
                $applied[] = ['discount_id'=>$d->id,'amount'=>$discountAmount,'type'=>$d->type];

                DiscountAudit::create([
                    'user_id'=>$userId,
                    'discount_id'=>$d->id,
                    'action'=>'applied',
                    'context'=>$context,
                    'amount_before'=>$original,
                    'amount_after'=>$remaining,
                    'amount_discounted'=>$discountAmount
                ]);

                if (!$d->stackable) break;
            }
            return [
                'original'       => $original,
                'final'          => $remaining,
                'applied'        => $applied,
                'total_discount' => $original - $remaining,
                'final_amount'   => $remaining,
            ];
        });
    }

    protected function round(float $value): float {
        $precision = $this->config['rounding']['precision'] ?? 2;
        $mode = $this->config['rounding']['mode'] ?? 'round';
        return match($mode) {
            'floor' => floor($value * (10**$precision)) / (10**$precision),
            'ceil' => ceil($value * (10**$precision)) / (10**$precision),
            default => round($value, $precision),
        };
    }
}
