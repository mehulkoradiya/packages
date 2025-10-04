<?php

namespace Vendor\NurseryManagementSystem\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BulkMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $channel, public ?string $subject, public string $content)
    {
        $this->onQueue('communications');
    }

    public function via(object $notifiable): array
    {
        return match ($this->channel) {
            'email' => ['mail'],
            'sms' => [\Vendor\NurseryManagementSystem\Notifications\Channels\LogSmsChannel::class],
            'whatsapp' => [\Vendor\NurseryManagementSystem\Notifications\Channels\LogWhatsAppChannel::class],
            default => ['mail'],
        };
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject ?? 'Message from Nursery')
            ->line($this->content);
    }

    public function toSms(object $notifiable): string
    {
        return $this->content;
    }

    public function toWhatsApp(object $notifiable): string
    {
        return $this->content;
    }
}
