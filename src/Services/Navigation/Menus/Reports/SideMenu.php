<?php

namespace GetCandy\Hub\Services\Navigation\Menus\Reports;

use GetCandy\Hub\Services\Navigation\Menus\Menu;
use GetCandy\Hub\Services\Navigation\NavItem;

class SideMenu extends Menu
{
    public function __construct()
    {
        $this->navItems = [
            new NavItem('hub::titles.sales-report', route('hub.reports.index'), 'sales-report'),
            new NavItem('hub::titles.product-attributes-report', route('hub.reports.products.attributes'), 'product-attributes-report'),
            new NavItem('hub::titles.shipping-report', route('hub.reports.shipping'), 'shipping-report'),
        ];
        $this->view = 'hub::menus.side-menu';
    }
}
