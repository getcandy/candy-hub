<?php

namespace GetCandy\Cms\Services\Navigation\Menus;

use GetCandy\Cms\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('Products', 'cm_products'),
            new NavItem('Collections', 'dashboard'),
            new NavItem('Catalogues', 'dashboard'),
            new NavItem('Media', 'dashboard'),
            new NavItem('Attributes', 'dashboard'),
            new NavItem('Aliases', 'dashboard')
        ];

        $this->navItems = [
            new NavItem('menus/top_menu.catalogue_manager', 'dashboard', $catalogueManagerItems),
            new NavItem('menus/top_menu.order_processing', 'dashboard'),
            new NavItem('menus/top_menu.marketing_suite', 'dashboard', $catalogueManagerItems),
            new NavItem('menus/top_menu.reports', 'dashboard'),
            new NavItem('menus/top_menu.settings', 'dashboard')
        ];

        $this->view = 'menus.top-menu';
    }
}
