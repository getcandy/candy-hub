<?php

namespace GetCandy\Cms\Services\Navigation\Menus\CatalogueManager;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Products', 'cm_products'),
            new NavItem('Collections', 'dashboard'),
            new NavItem('Catalogues', 'dashboard'),
            new NavItem('Media', 'dashboard'),
            new NavItem('Attributes', 'dashboard'),
            new NavItem('Aliases', 'dashboard')
        ];

        $this->view = 'menus.side-menu';
    }
}
