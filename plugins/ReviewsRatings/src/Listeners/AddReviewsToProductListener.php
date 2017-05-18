<?php
namespace GetCandy\Plugins\ReviewsRatings\Listeners;

use Neon\Commerce\Events\ViewProductEvent;

class AddReviewsToProductListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(ViewProductEvent $event)
    {
        dd('Hit the listener!');
    }
}
