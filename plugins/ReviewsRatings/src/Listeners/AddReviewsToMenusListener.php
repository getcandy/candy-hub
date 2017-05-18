<?php
namespace GetCandy\Plugins\ReviewsRatings\Listeners;

use GetCandy\Cms\Services\Navigation\Menus\CatalogueManager\SideMenu;
use GetCandy\Cms\Services\Navigation\Menus\TopMenu;
use GetCandy\Cms\Services\Navigation\NavItem;

class AddReviewsToMenusListener
{
    public function handle($menu)
    {
        if (get_class($menu) == SideMenu::class) {
            // Add button to side menu
            $menu->addItem(new NavItem('Reviews', route('reviews')));
        }

        if (get_class($menu) == TopMenu::class) {
            // Add button to side menu
            $menu->addItem(new NavItem('Reviews', 'api/v1/reviews'), 0);
        }

        return $menu;
    }
}
