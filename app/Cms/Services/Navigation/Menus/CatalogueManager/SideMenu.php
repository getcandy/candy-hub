<?php

namespace GetCandy\Cms\Services\Navigation\Menus\CatalogueManager;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Products', route('cm_products')),
            new NavItem('Collections', route('cm_collections')),
            new NavItem('Categories', route('cm_categories')),
            new NavItem('Media', route('dashboard')),
            new NavItem('Attributes', route('dashboard')),
            new NavItem('Aliases', route('dashboard'))
        ];

        $this->view = 'menus.side-menu';
    }
}
