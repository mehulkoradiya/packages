<?php

namespace Vendor\NurseryManagementSystem\Traits;

use Illuminate\Notifications\Notifiable;

trait NotifiableContact
{
    use Notifiable;

    public function routeNotificationForSms(): ?string
    {
        return $this->phone ?? null;
    }

    public function routeNotificationForWhatsapp(): ?string
    {
        return $this->phone ?? null;
    }
}
