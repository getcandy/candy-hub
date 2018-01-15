<?php
namespace GetCandy\Cms\Services\Navigation\Menus\OrderProcessing;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Orders', route('hub.orders.index')),
            new NavItem('Returns', route('hub.orders.index')),
            new NavItem('Customers', route('hub.customers.index')),
            new NavItem('Shipping Methods', route('hub.shipping.index')),
            new NavItem('Shipping Zones', route('hub.shipping.zones')),
        ];

        $this->view = 'menus.side-menu';
    }
}
