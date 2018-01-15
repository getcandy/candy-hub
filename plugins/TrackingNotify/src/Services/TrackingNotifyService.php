<?php

namespace GetCandy\Plugins\TrackingNotify\Services;

use Mail;
use GetCandy\Plugins\TrackingNotify\Mailables\TrackingMailable;

class TrackingNotifyService
{
    public function sendMailer($order)
    {
        Mail::to($order->contact_email)->send(new TrackingMailable($order));
    }
}
