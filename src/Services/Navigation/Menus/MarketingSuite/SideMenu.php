<?php
namespace GetCandy\Hub\Services\Navigation\Menus\MarketingSuite;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Discounts', route('hub.discounts.index'))
        ];

        $this->view = 'hub::menus.side-menu';
    }
}
