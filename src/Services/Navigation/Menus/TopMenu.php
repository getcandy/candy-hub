<?php

namespace GetCandy\Hub\Services\Navigation\Menus;

use GetCandy\Hub\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('titles.products', route('hub.products.index')),
            new NavItem('titles.collections', route('hub.collections.index')),
            new NavItem('titles.categories', route('hub.categories.index')),
            // new NavItem('Media', route('hub.index')),
            // new NavItem('Attributes', route('hub.index')),
            // new NavItem('Aliases', route('hub.index'))
        ];

        $orderProcessingItems = [
            new NavItem('titles.orders', route('hub.orders.index')),
            // new NavItem('Returns', route('hub.orders.index')),
            new NavItem('titles.customers', route('hub.customers.index')),
            new NavItem('titles.shipping', route('hub.shipping.index')),
        ];

        $marketingItems = [
            new NavItem('titles.discounts', route('hub.discounts.index'))
        ];

        $reportItems = [
            new NavItem('titles.products', route('hub.products.index')),
            // new NavItem('Customers', route('hub.products.index')),
        ];

        $settingItems = [
            // new NavItem('General', route('hub.products.index')),
            // new NavItem('Localisation', route('hub.products.index')),
            new NavItem('titles.products', route('hub.products.index')),
            // new NavItem('Channels', route('hub.products.index')),
            // new NavItem('Categories', route('hub.products.index')),
        ];


        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', route('hub.products.index'), $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', route('hub.orders.index'), $orderProcessingItems),
            new NavItem('menus/top_menu.marketing_suite', route('hub.discounts.index'), $marketingItems),
            new NavItem('menus/top_menu.reports', route('hub.products.index'), $reportItems),
            new NavItem('menus/top_menu.settings', route('hub.products.index'), $settingItems)
        ];

        $this->view = 'hub::menus.top-menu';
    }
}
