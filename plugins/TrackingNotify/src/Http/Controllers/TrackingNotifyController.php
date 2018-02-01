<?php
namespace GetCandy\Plugins\TrackingNotify\Http\Controllers;

use Illuminate\Http\Request;

class TrackingNotifyController
{
    public function sendmailable($orderId, Request $request)
    {
        $order = app('api')->orders()->getByHashedId($orderId);
        app()->trackingnotify->sendMailer($order);
    }
}
