<?php
namespace GetCandy\Cms\Services\Navigation\Menus\MarketingSuite;

use GetCandy\Cms\Services\Navigation\Menus\Menu;
use GetCandy\Cms\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('Discounts', route('hub.discounts.index'))
        ];

        $this->view = 'menus.side-menu';
    }
}
