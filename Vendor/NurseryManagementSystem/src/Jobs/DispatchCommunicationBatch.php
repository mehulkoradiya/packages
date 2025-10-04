<?php

namespace Vendor\NurseryManagementSystem\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vendor\NurseryManagementSystem\Models\CommBatch;
use Vendor\NurseryManagementSystem\Models\CommRecipient;
use Vendor\NurseryManagementSystem\Notifications\BulkMessage;

class DispatchCommunicationBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public CommBatch $batch)
    {
        $this->onQueue('communications');
    }

    public function handle(): void
    {
        $this->batch->load('recipients.parent');
        foreach ($this->batch->recipients as $recipient) {
            try {
                if ($recipient->parent) {
                    $recipient->parent->notify(new BulkMessage(
                        $this->batch->channel,
                        $this->batch->subject,
                        $this->batch->content
                    ));
                }
                $recipient->status = 'sent';
                $recipient->error = null;
            } catch (\Throwable $e) {
                $recipient->status = 'failed';
                $recipient->error = $e->getMessage();
            }
            $recipient->save();
        }

        $this->batch->sent = $this->batch->recipients()->where('status', 'sent')->count();
        $this->batch->save();
    }
}
