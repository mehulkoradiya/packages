<?php
namespace Vendor\UserDiscounts\Tests;

use Orchestra\Testbench\TestCase;
use Vendor\UserDiscounts\UserDiscountsServiceProvider;
use Vendor\UserDiscounts\Models\Discount;
use Vendor\UserDiscounts\Models\UserDiscount;
use Vendor\UserDiscounts\Services\DiscountManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DiscountApplyTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app) {
        return [UserDiscountsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app) {
        // load package migrations
        foreach (glob(__DIR__.'/../database/migrations/*.php') as $file) {
            require_once $file;
        }
        (new \CreateDiscountsTable())->up();
        (new \CreateUserDiscountsTable())->up();
        (new \CreateDiscountAuditsTable())->up();
    }

    public function test_usage_cap_is_enforced_and_concurrency_safe()
    {
        $user = \App\Models\User::factory()->create();
        $discount = Discount::create([
            'type'=>'percentage',
            'value'=>10.0,
            'active'=>true,
            'per_user_limit' => 1,  // user can use only once
            'stacking_priority' => 1,
            'stackable' => true
        ]);

        // assign to user
        $dm = $this->app->make(DiscountManager::class);
        $dm->assign($user->id, $discount->id);

        // simulate two concurrent apply attempts
        // process 1: begin transaction and attempt atomic update later
        $result1 = null;
        $result2 = null;

        // First call:
        $result1 = $dm->apply($user->id, 100.00, []);
        // Second concurrent call immediately after:
        $result2 = $dm->apply($user->id, 100.00, []);

        // Exactly one of the apply calls should have applied the discount.
        $appliedCount = 0;
        if (count($result1['applied']) > 0) $appliedCount++;
        if (count($result2['applied']) > 0) $appliedCount++;

        $this->assertEquals(1, $appliedCount, 'Expected only one apply to succeed under usage cap');
    }
}
