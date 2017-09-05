<?php

namespace GetCandy\Cms\Services\Navigation\Menus;

use GetCandy\Cms\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('Products', route('cm_products')),
            new NavItem('Collections', route('cm_collections')),
            new NavItem('Categories', route('cm_categories')),
            new NavItem('Media', route('dashboard')),
            new NavItem('Attributes', route('dashboard')),
            new NavItem('Aliases', route('dashboard'))
        ];

        $orderProcessingItems = [
            new NavItem('Orders', route('dashboard')),
            new NavItem('Returns', route('dashboard')),
            new NavItem('Customers', route('dashboard')),
        ];

        $marketingItems = [
            new NavItem('Coupons', route('dashboard'))
        ];

        $reportItems = [
            new NavItem('Orders', route('dashboard')),
            new NavItem('Customers', route('dashboard')),
        ];

        $settingItems = [
            new NavItem('General', route('dashboard')),
            new NavItem('Localisation', route('dashboard')),
            new NavItem('Products', route('dashboard')),
            new NavItem('Channels', route('dashboard')),
            new NavItem('Categories', route('dashboard')),
        ];


        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', route('dashboard'), $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', route('dashboard'), $orderProcessingItems),
            new NavItem('menus/top_menu.marketing_suite', route('dashboard'), $marketingItems),
            new NavItem('menus/top_menu.reports', route('dashboard'), $reportItems),
            new NavItem('menus/top_menu.settings', route('dashboard'), $settingItems)
        ];

        $this->view = 'menus.top-menu';
    }
}
