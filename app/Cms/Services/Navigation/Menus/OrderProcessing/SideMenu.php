<?php
namespace GetCandy\Cms\Services\Navigation\Menus\OrderProcessing;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Orders', route('hub.orders.index'), [], 'credit-card'),
            new NavItem('Returns', route('hub.orders.index'), [], 'undo'),
            new NavItem('Customers', route('hub.orders.index'), [], 'users'),
            new NavItem('Shipping Methods', route('hub.shipping.index'), [], 'truck'),
            new NavItem('Shipping Zones', route('hub.shipping.zones'), [], 'globe'),
        ];

        $this->view = 'menus.side-menu';
    }
}
