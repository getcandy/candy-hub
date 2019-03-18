<?php

namespace GetCandy\Hub\Services\Navigation\Menus\CatalogueManager;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavBreak;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('hub::titles.products', route('hub.products.index'), 'products'),
            new NavItem('hub::titles.collections', route('hub.collections.index'), 'collections'),
            new NavItem('hub::titles.categories', route('hub.categories.index'), 'categories'),
            new NavBreak(),
            new NavItem('hub::titles.attributes', route('hub.attributes.index'), 'attributes'),
            new NavItem('hub::titles.attribute-groups', route('hub.attribute-groups.index'), 'attribute-groups'),
            new NavItem('hub::titles.product-families', route('hub.product-families.index'), 'product-families'),
            // new NavItem('Media', route('hub.index')),
            // new NavItem('Attributes', route('hub.index')),
            // new NavItem('Aliases', route('hub.index')),
            // new NavItem('Discounts', route('hub.discounts.index'))
        ];

        $this->view = 'hub::menus.side-menu';
        $this->area = 'catalogue-manager';
    }
}
