<?php
namespace GetCandy\Hub\Services\Navigation\Menus\OrderProcessing;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Orders', route('hub.orders.index')),
            // new NavItem('Returns', route('hub.orders.index')),
            new NavItem('Customers', route('hub.customers.index')),
            new NavItem('Shipping Methods', route('hub.shipping.index')),
            new NavItem('Shipping Zones', route('hub.shipping.zones')),
        ];

        $this->view = 'hub::menus.side-menu';
    }
}
