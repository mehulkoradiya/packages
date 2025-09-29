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

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function assign(int $userId, int $discountId, ?int $usageLimit = null) {
        $ud = UserDiscount::firstOrCreate(
            ['user_id' => $userId, 'discount_id' => $discountId],
            ['usage_limit' => $usageLimit, 'assigned_at' => now()]
        );
        event(new \Vendor\UserDiscounts\Events\DiscountAssigned($ud));
        DiscountAudit::create(['user_id'=>$userId,'discount_id'=>$discountId,'action'=>'assigned']);
        return $ud;
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
            })->get();

        // exclude revoked or per-user ineligible
        return $discounts->filter(function($d) use ($userId) {
            $ud = UserDiscount::where(['user_id'=>$userId,'discount_id'=>$d->id])->first();
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

        DB::beginTransaction();
        try {
            $totalPercentApplied = 0.0;
            foreach ($eligible as $d) {
                // skip expired/inactive guard
                if (!$d->active) continue;

                // fetch or create user_discount entry
                $userDiscount = UserDiscount::firstOrCreate(
                    ['user_id'=>$userId,'discount_id'=>$d->id],
                    ['usage_limit'=>null,'usage_count'=>0,'assigned_at'=>now()]
                );

                $limit = $userDiscount->usage_limit ?? $d->per_user_limit;

                // atomic increment attempt
                $affected = DB::update(
                    'UPDATE user_discounts SET usage_count = usage_count + 1 WHERE id = ? AND (usage_limit IS NULL OR usage_count < ?)',
                    [$userDiscount->id, $limit ?? PHP_INT_MAX]
                );
                if ($affected === 0) {
                    continue; // usage exhausted / raced
                }

                // compute discount amount
                if ($d->type === 'percentage') {
                    $percent = (float)$d->value;
                    // enforce max_total_percentage cap
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
                    // revert usage increment if nothing applied (optional)
                    DB::update('UPDATE user_discounts SET usage_count = usage_count - 1 WHERE id = ?', [$userDiscount->id]);
                    continue;
                }

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

                // increment global usage safely if needed
                if (!is_null($d->max_uses)) {
                    DB::update('UPDATE discounts SET usage_count = usage_count + 1 WHERE id = ? AND (max_uses IS NULL OR usage_count < ?)', [$d->id, $d->max_uses]);
                } else {
                    DB::update('UPDATE discounts SET usage_count = usage_count + 1 WHERE id = ?', [$d->id]);
                }

                // stop if non-stackable
                if (!$d->stackable) break;
            }
            DB::commit();
            return ['original'=>$original, 'final'=>$remaining, 'applied'=>$applied];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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
