<?php

namespace GetCandy\Cms\Services\Navigation\Menus;

use GetCandy\Cms\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('Products', route('hub.products.index')),
            new NavItem('Collections', route('hub.collections.index')),
            new NavItem('Categories', route('hub.categories.index')),
            new NavItem('Media', route('hub.index')),
            new NavItem('Attributes', route('hub.index')),
            new NavItem('Aliases', route('hub.index'))
        ];

        $orderProcessingItems = [
            new NavItem('Orders', route('hub.orders.index')),
            new NavItem('Returns', route('hub.products.index')),
            new NavItem('Customers', route('hub.products.index')),
        ];

        $marketingItems = [
            new NavItem('Coupons', route('hub.products.index'))
        ];

        $reportItems = [
            new NavItem('Orders', route('hub.products.index')),
            new NavItem('Customers', route('hub.products.index')),
        ];

        $settingItems = [
            new NavItem('General', route('hub.products.index')),
            new NavItem('Localisation', route('hub.products.index')),
            new NavItem('Products', route('hub.products.index')),
            new NavItem('Channels', route('hub.products.index')),
            new NavItem('Categories', route('hub.products.index')),
        ];


        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', route('hub.products.index'), $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', route('hub.products.index'), $orderProcessingItems),
            new NavItem('menus/top_menu.marketing_suite', route('hub.products.index'), $marketingItems),
            new NavItem('menus/top_menu.reports', route('hub.products.index'), $reportItems),
            new NavItem('menus/top_menu.settings', route('hub.products.index'), $settingItems)
        ];

        $this->view = 'menus.top-menu';
    }
}
