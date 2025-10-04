<?php

namespace Vendor\NurseryManagementSystem\Notifications\Channels;

use Illuminate\Notifications\Notification;

class LogSmsChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $message = method_exists($notification, 'toSms') ? $notification->toSms($notifiable) : null;
        if ($message) {
            \Log::info('NMS SMS', ['to' => $notifiable->routeNotificationFor('sms'), 'message' => $message]);
        }
    }
}
