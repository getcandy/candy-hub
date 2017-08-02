<?php

namespace GetCandy\Cms\Services\Navigation\Menus;

use GetCandy\Cms\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('Products', route('cm_products')),
            new NavItem('Collections', route('dashboard')),
            new NavItem('Categories', route('cm_categories')),
            new NavItem('Media', route('dashboard')),
            new NavItem('Attributes', route('dashboard')),
            new NavItem('Aliases', route('dashboard'))
        ];

        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', route('dashboard'), $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', route('dashboard')),
            new NavItem('menus/top_menu.marketing_suite', route('dashboard'), $catalogueManagerItems),
            new NavItem('menus/top_menu.reports', route('dashboard')),
            new NavItem('menus/top_menu.settings', route('dashboard'))
        ];

        $this->view = 'menus.top-menu';
    }
}
