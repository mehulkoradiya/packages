# Task B – User Discounts (Laravel Package)

A reusable Laravel package that provides **user-level discounts** with deterministic stacking, usage caps, concurrency safety, and full test coverage.

---

## ✨ Features

- **Reusable Laravel Package** (PSR-4, install via Composer)  
- **Discount Management** (assign, revoke, apply, eligibility checks)  
- **Deterministic Stacking** (percentage + fixed discounts with configurable order)  
- **Usage Caps** per user & per discount  
- **Concurrency Safe** (atomic updates to prevent double increments)  
- **Events** fired for assigned, revoked, and applied discounts  
- **Auditing** (all discount applications logged)  
- **Configurable** (stacking order, rounding, max percentage cap)  
- **Automated Tests** (unit + feature tests)  

---

## 📦 Installation

Add repository in your main Laravel app (e.g. Task A):

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../Packages/Vendor/UserDiscounts"
    }
  ]
}
```

Require the package:

```bash
composer require vendor/user-discounts:dev-main
```

---

## ⚙️ Configuration

Publish the config:

```bash
php artisan vendor:publish --provider="Vendor\\UserDiscounts\\UserDiscountsServiceProvider" --tag="config"
```

`config/user_discounts.php`:

```php
return [
    'max_total_percentage' => 50, // cap on stacked percentage discounts
    'rounding' => [
        'precision' => 2,
        'mode' => 'round', // round | floor | ceil
    ],
];
```

---

## 🗄 Migrations

The package includes migrations for:

- `discounts` – master table of all discounts  
- `user_discounts` – per-user assigned discounts + usage counters  
- `discount_audits` – logs every assignment, revocation, and application  

Run migrations:

```bash
php artisan migrate
```

---

## 📚 Models

- `Discount` → defines discount type (`percentage` / `fixed`), value, stacking rules, usage caps  
- `UserDiscount` → tracks which users have which discounts, plus usage counts  
- `DiscountAudit` → audit log  

---

## 🚀 Usage

### 1. Assign a Discount
```php
use Vendor\UserDiscounts\Services\DiscountManager;

$manager = app(DiscountManager::class);

$manager->assign($userId, $discountId);
```

### 2. Revoke a Discount
```php
$manager->revoke($userId, $discountId);
```

### 3. Check Eligible Discounts
```php
$eligible = $manager->eligibleFor($userId);

foreach ($eligible as $discount) {
    echo $discount->name;
}
```

### 4. Apply Discounts
```php
$result = $manager->apply($userId, 200.00, ['order_id' => 123]);

// Result example:
/*
[
  "original" => 200.00,
  "final" => 150.00,
  "applied" => [
    [ "discount_id" => 1, "amount" => 30.00, "type" => "percentage" ],
    [ "discount_id" => 2, "amount" => 20.00, "type" => "fixed" ]
  ]
]
*/
```

---

## 🎯 Events

- `DiscountAssigned(UserDiscount $userDiscount)`  
- `DiscountRevoked(UserDiscount $userDiscount)`  
- `DiscountApplied(int $userId, Discount $discount, ?UserDiscount $userDiscount, float $before, float $after, float $discountAmount, array $context)`  

You can listen to these events in your app:

```php
DiscountApplied::class => [
    App\Listeners\SendDiscountAppliedNotification::class,
],
```

---

## 🧪 Testing

Run the package tests:

```bash
cd packages/Vendor/UserDiscounts
vendor/bin/phpunit
```

---

## 🔐 Rules Enforced

- Expired or inactive discounts are ignored  
- Per-user usage caps enforced  
- Application is deterministic and idempotent  
- Concurrent `apply` calls do not double-increment usage  
- Revoked discounts cannot be applied  

---

## 📜 License

MIT License © 2025
