<?php
namespace GetCandy\Plugins\ReviewsRatings\Listeners;

class AddReviewsCssListener
{
    public function handle($items)
    {
        $items[] = '<link stuff>';

        return $items;
    }
}
