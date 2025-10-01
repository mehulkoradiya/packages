<?php

namespace Vendor\UserDiscounts\Tests;


use Orchestra\Testbench\TestCase;
use Vendor\UserDiscounts\UserDiscountsServiceProvider;
use Vendor\UserDiscounts\Models\Discount;
use Vendor\UserDiscounts\Services\DiscountManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Vendor\UserDiscounts\Tests\TestUser;

class DiscountApplyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [UserDiscountsServiceProvider::class];
    }

    /**
     * Define database migrations.
     * This method is executed after the application environment is fully set up.
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations'); //  fake users migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations'); // package migrations
    }


    protected function getEnvironmentSetUp($app)
    {
        // Setup in-memory SQLite for testing
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:', 
            'prefix' => '',
        ]);

        $app['config']->set('user-discounts', [
            'stacking_order'       => ['stacking_priority','percentage_first'],
            'max_total_percentage' => 50.0,
            'rounding'             => ['mode' => 'round', 'precision' => 2],
        ]);
    }

    public function test_usage_cap_is_enforced_and_concurrency_safe()
    {
        // Create a test user
        $user = TestUser::create([
            'name'     => $this->faker->name,
            'email'    => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
        ]);

        // Create a discount with usage cap 1
        $discount = Discount::create([
            'type'             => 'percentage',
            'value'            => 10.0,
            'active'           => true,
            'per_user_limit'   => 1,
            'stacking_priority'=> 1,
            'stackable'        => true,
        ]);

        // Assign discount to user
        $dm = app(DiscountManager::class);
        $dm->assign($user->id, $discount->id);

        // Try applying twice (simulate sequential access that hits the usage cap)
        $result1 = $dm->apply($user->id, 100.00, []);
        $result2 = $dm->apply($user->id, 100.00, []);

        $appliedCount = 0;
        if (count($result1['applied'] ?? []) > 0) $appliedCount++;
        if (count($result2['applied'] ?? []) > 0) $appliedCount++;

        $this->assertEquals(
            1,
            $appliedCount,
            'Expected only one apply to succeed under usage cap'
        );

        // Assert the database was updated correctly
        $this->assertDatabaseHas('user_discounts', [
            'user_id' => $user->id,
            'discount_id' => $discount->id,
            'usage_count' => 1,
        ]);
        
        // Assert the results of the successful application
        $this->assertEquals(10.00, $result1['total_discount'] ?? 0.0);
        $this->assertEquals(90.00, $result1['final_amount'] ?? 100.00);

        // Assert the results of the failed application
        $this->assertEquals(0.00, $result2['total_discount'] ?? 0.0);
        $this->assertEquals(100.00, $result2['final_amount'] ?? 100.00);
    }
}
