<?php

namespace GetCandy\Hub\Services\Navigation\Menus\OrderProcessing;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('titles.orders', route('hub.orders.index'), 'orders'),
            // new NavItem('Returns', route('hub.orders.index')),
            new NavItem('titles.customers', route('hub.customers.index'), 'customers'),
            new NavItem('titles.shipping_methods', route('hub.shipping.index'), 'shipping_methods'),
            new NavItem('titles.shipping_zones', route('hub.shipping_zones.index'), 'shipping_zones'),
        ];

        $this->view = 'hub::menus.side-menu';
    }
}
