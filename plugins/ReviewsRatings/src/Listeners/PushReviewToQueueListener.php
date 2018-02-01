<?php
namespace GetCandy\Plugins\ReviewsRatings\Listeners;

use GetCandy\Api\Orders\Events\OrderProcessedEvent;

class PushReviewToQueueListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(OrderProcessedEvent $event)
    {
        $order = $event->order;
        $details = $order->billingDetails;

        $data = [
            'store' => config('services.reviews.merchant'),
            'apikey' => config('services.reviews.key'),
            'name' => $details['firstname'] . ' ' . $details['lastname'],
            'email' => $order->contact_email,
            'order_id' => $order->encodedId()
        ];

        // Post request
        $url = "https://api.reviews.co.uk/merchant/invitation";

        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $result = curl_exec($ch);
    }
}
