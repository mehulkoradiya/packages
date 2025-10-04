<?php

namespace Vendor\NurseryManagementSystem\Models;

use Illuminate\Database\Eloquent\Model;

class CommRecipient extends Model
{
    protected $table = 'nms_comm_recipients';

    protected $fillable = [
        'batch_id', 'parent_id', 'student_id', 'status', 'error',
    ];

    public function batch()
    {
        return $this->belongsTo(CommBatch::class, 'batch_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentGuardian::class, 'parent_id');
    }
}
