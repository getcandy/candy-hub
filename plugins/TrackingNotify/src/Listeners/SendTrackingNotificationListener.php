<?php
namespace GetCandy\Plugins\TrackingNotify\Listeners;

use GetCandy\Api\Orders\Events\OrderBeforeSavedEvent;

class SendTrackingNotificationListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(OrderBeforeSavedEvent $event)
    {
        if ($this->shouldNotify($event->order)) {
            app()->trackingnotify->sendMailer($event->order);
        }
    }

    private function shouldNotify($order)
    {
        return strtolower($order->status) == 'dispatched' && $order->isDirty('status');
    }
}
