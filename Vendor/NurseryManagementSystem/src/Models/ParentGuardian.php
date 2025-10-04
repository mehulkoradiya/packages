<?php

namespace Vendor\NurseryManagementSystem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentGuardian extends Model
{
    use HasFactory;
    use \Vendor\NurseryManagementSystem\Traits\NotifiableContact;

    protected $table = 'nms_parents';

    protected $fillable = [
        'user_id', 'name', 'phone', 'email',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
