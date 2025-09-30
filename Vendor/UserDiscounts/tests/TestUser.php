<?php

namespace Vendor\UserDiscounts\Tests;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TestUser extends Authenticatable
{
    protected $table = 'users';
    protected $guarded = [];
    protected $fillable = ['name', 'email', 'password'];
}
