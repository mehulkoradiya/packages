<?php

namespace Vendor\NurseryManagementSystem\Models;

use Illuminate\Database\Eloquent\Model;

class CommBatch extends Model
{
    protected $table = 'nms_comm_batches';

    protected $fillable = [
        'channel', 'subject', 'content', 'total', 'sent', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function recipients()
    {
        return $this->hasMany(CommRecipient::class, 'batch_id');
    }
}
