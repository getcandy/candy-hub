<?php

namespace GetCandy\Hub\Services\Navigation\Menus\CatalogueManager;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Products', route('hub.products.index')),
            new NavItem('Collections', route('hub.collections.index')),
            new NavItem('Categories', route('hub.categories.index')),
            // new NavItem('Media', route('hub.index')),
            // new NavItem('Attributes', route('hub.index')),
            // new NavItem('Aliases', route('hub.index')),
            // new NavItem('Discounts', route('hub.discounts.index'))
        ];

        $this->view = 'menus.side-menu';
    }
}
