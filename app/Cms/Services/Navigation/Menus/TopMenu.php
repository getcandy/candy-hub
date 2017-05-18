<?php

namespace GetCandy\Cms\Services\Navigation\Menus;

use GetCandy\Cms\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('Products', 'cms_cm_products'),
            new NavItem('Collections', 'cms_logout'),
            new NavItem('Catalogues', 'cms_logout'),
            new NavItem('Media', 'cms_logout'),
            new NavItem('Attributes', 'cms_logout'),
            new NavItem('Aliases', 'cms_logout')
        ];

        $this->navItems = [
            new NavItem('getcandy_cms::menus/top_menu.catalogue_manager', 'cms_logout', $catalogueManagerItems),
            new NavItem('getcandy_cms::menus/top_menu.order_processing', 'cms_logout'),
            new NavItem('getcandy_cms::menus/top_menu.marketing_suite', 'cms_logout', $catalogueManagerItems),
            new NavItem('getcandy_cms::menus/top_menu.reports', 'cms_logout'),
            new NavItem('getcandy_cms::menus/top_menu.settings', 'cms_logout')
        ];

        $this->view = 'getcandy_cms::menus.top-menu';
    }
}
