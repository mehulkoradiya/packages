<?php

namespace Vendor\UserDiscounts\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vendor\UserDiscounts\Models\Discount;
use Vendor\UserDiscounts\Models\UserDiscount;

class DiscountApplied
{
    use Dispatchable, SerializesModels;

    public int $userId;
    public Discount $discount;
    public ?UserDiscount $userDiscount;
    public float $amountBefore;
    public float $amountAfter;
    public float $discountAmount;
    public array $context;

    public function __construct(
        int $userId,
        Discount $discount,
        ?UserDiscount $userDiscount,
        float $amountBefore,
        float $amountAfter,
        float $discountAmount,
        array $context = []
    ) {
        $this->userId = $userId;
        $this->discount = $discount;
        $this->userDiscount = $userDiscount;
        $this->amountBefore = $amountBefore;
        $this->amountAfter = $amountAfter;
        $this->discountAmount = $discountAmount;
        $this->context = $context;
    }
}
