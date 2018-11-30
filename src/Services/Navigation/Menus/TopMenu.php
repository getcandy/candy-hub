<?php

namespace GetCandy\Hub\Services\Navigation\Menus;

use GetCandy\Hub\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('titles.products', route('hub.products.index'), 'products'),
            new NavItem('titles.collections', route('hub.collections.index'), 'collections'),
            new NavItem('titles.categories', route('hub.categories.index'), 'categories'),
        ];

        $orderProcessingItems = [
            new NavItem('titles.orders', route('hub.orders.index'), 'orders'),
            new NavItem('titles.customers', route('hub.customers.index'), 'customers'),
            new NavItem('titles.shipping', route('hub.shipping.index'), 'shipping'),
        ];

        $marketingItems = [
            new NavItem('titles.discounts', route('hub.discounts.index'), 'discounts'),
        ];

        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', route('hub.products.index'), null, $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', route('hub.orders.index'), null, $orderProcessingItems),
            new NavItem('menus/top_menu.marketing_suite', route('hub.discounts.index'), null, $marketingItems),
        ];

        $this->view = 'hub::menus.top-menu';
    }
}
