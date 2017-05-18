<?php
namespace GetCandy\Plugins\ReviewsRatings\Listeners;

class AddReviewsScriptsListener
{
    public function handle($items)
    {
        $items[] = '<link stuff>';

        return $items;
    }
}
