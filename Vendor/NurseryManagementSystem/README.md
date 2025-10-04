# Nursery Management System (Laravel Package)

A Laravel 12-ready package providing nursery management features: classes, students, attendance, invoices, payments (stubs), parent communications (email/SMS/WhatsApp via queues), and dashboards. Built with Blade + Bootstrap 5 and Redis queues.

## Installation

1. Require this package in your Laravel 12 app (path repo or composer):

```json
{
  "repositories": [
    {"type": "path", "url": "./Vendor/NurseryManagementSystem"}
  ]
}
```

```bash
composer require vendor/nursery-management-system:dev-main
```

Add the service provider if package discovery is disabled:

```php
// config/app.php
'providers' => [
    // ...
    Vendor\NurseryManagementSystem\NurseryManagementSystemServiceProvider::class,
];
```

2. Publish assets and run migrations:

```bash
php artisan vendor:publish --tag=nms-config --tag=nms-views --tag=nms-migrations
php artisan migrate
php artisan db:seed --class=Vendor\\NurseryManagementSystem\\Database\\Seeders\\NmsRolesSeeder
```

3. Configure roles

- Recommended: install `spatie/laravel-permission` and assign roles `Admin`, `Teacher`, `Parent` to users.
- Fallback: add a `role` string column on `users` and use provided middleware.

4. Configure queues and mail

- Use Redis for queues: `QUEUE_CONNECTION=redis`
- Set mail and SMS/WhatsApp drivers in `config/nms.php` or env.

Example `.env` additions:

```dotenv
NMS_SMS_DRIVER=log
NMS_WHATSAPP_DRIVER=log
NMS_PAYMENT_PROVIDER=manual
NMS_CURRENCY=USD
```

5. Visit the dashboard at `/nursery` after logging in.

## Modules
- Class management
- Students
- Attendance + reports
- Invoices + payments (provider stubs)
- Communications (bulk email/SMS/WhatsApp) via queues
- Admin dashboard

## Theming
The package ships with a child-friendly Bootstrap 5 theme and responsive layout.
