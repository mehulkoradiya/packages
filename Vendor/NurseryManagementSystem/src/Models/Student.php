<?php

namespace Vendor\NurseryManagementSystem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'nms_students';

    protected $fillable = [
        'classroom_id', 'parent_id', 'first_name', 'last_name', 'dob', 'gender', 'roll_no', 'address',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function parentGuardian()
    {
        return $this->belongsTo(ParentGuardian::class, 'parent_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }
}
