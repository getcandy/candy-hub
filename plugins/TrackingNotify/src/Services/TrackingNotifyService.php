<?php

namespace GetCandy\Plugins\TrackingNotify\Services;

use Mail;
use GetCandy\Plugins\TrackingNotify\Mailables\TrackingMailable;

class TrackingNotifyService
{
    public function sendMailer($order)
    {
        $email = $order->contact_email;

        if (!$email && $order->user) {
            $email = $order->user->email;
        }

        if ($email) {
            Mail::to()->send(new TrackingMailable($order));
            Mail::to(config('mail.from.address'))->send(new TrackingMailable($order));
        }
    }
}
