<?php

namespace Vendor\NurseryManagementSystem\Notifications\Channels;

use Illuminate\Notifications\Notification;

class LogWhatsAppChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $message = method_exists($notification, 'toWhatsApp') ? $notification->toWhatsApp($notifiable) : null;
        if ($message) {
            \Log::info('NMS WhatsApp', ['to' => $notifiable->routeNotificationFor('whatsapp'), 'message' => $message]);
        }
    }
}
