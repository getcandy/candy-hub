<?php

namespace GetCandy\Cms\Services\Navigation\Menus\CatalogueManager;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Products', 'cms_cm_products'),
            new NavItem('Collections', 'cms_logout'),
            new NavItem('Catalogues', 'cms_logout'),
            new NavItem('Media', 'cms_logout'),
            new NavItem('Attributes', 'cms_logout'),
            new NavItem('Aliases', 'cms_logout')
        ];

        $this->view = 'getcandy_cms::menus.side-menu';
    }
}
