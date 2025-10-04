<?php

namespace Vendor\NurseryManagementSystem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'nms_classrooms';

    protected $fillable = [
        'name', 'section', 'capacity', 'notes',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }
}
