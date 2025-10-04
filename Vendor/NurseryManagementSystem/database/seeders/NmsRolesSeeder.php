<?php

namespace Vendor\NurseryManagementSystem\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class NmsRolesSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists('Spatie\\Permission\\Models\\Role')) {
            $roles = ['Admin', 'Teacher', 'Parent'];
            foreach ($roles as $name) {
                \Spatie\Permission\Models\Role::findOrCreate($name);
            }
        } else {
            if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'role')) {
                Schema::table('users', function ($table) {
                    $table->string('role')->nullable();
                });
            }
        }

        if (class_exists('App\\Models\\User')) {
            $userModel = app('App\\Models\\User');
            $admin = $userModel::firstOrCreate(
                ['email' => 'admin@example.com'],
                ['name' => 'Admin', 'password' => Hash::make('password')]
            );
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('Admin');
            } else {
                $admin->role = 'Admin';
                $admin->save();
            }
        }
    }
}
