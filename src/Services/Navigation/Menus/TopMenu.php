<?php

namespace GetCandy\Hub\Services\Navigation\Menus;

use GetCandy\Hub\Services\Navigation\NavItem;

class TopMenu extends Menu
{
    protected $area = 'top-menu';

    public function __construct()
    {
        $catalogueManagerItems = [
            new NavItem('hub::titles.products', route('hub.products.index'), 'products'),
            new NavItem('hub::titles.collections', route('hub.collections.index'), 'collections'),
            new NavItem('hub::titles.categories', route('hub.categories.index'), 'categories'),
        ];

        $orderProcessingItems = [
            new NavItem('hub::titles.orders', route('hub.orders.index'), 'orders'),
            new NavItem('hub::titles.customers', route('hub.customers.index'), 'customers'),
            new NavItem('hub::titles.shipping', route('hub.shipping.index'), 'shipping'),
        ];

        $marketingItems = [
            new NavItem('hub::titles.discounts', route('hub.discounts.index'), 'discounts'),
        ];

        $reportItems = [

        ];

        $this->navItems = [
            new NavItem('hub::menus/top_menu.catalogue_manager', route('hub.products.index'), null, $catalogueManagerItems),
            new NavItem('hub::menus/top_menu.order_processing', route('hub.orders.index'), null, $orderProcessingItems),
            new NavItem('hub::menus/top_menu.marketing_suite', route('hub.discounts.index'), null, $marketingItems),
            new NavItem('hub::menus/top_menu.reports', route('hub.reports.index'), null, $reportItems),
        ];



        $this->view = 'hub::menus.top-menu';
        $this->area = 'top-menu';
    }
}
