<?php

namespace GetCandy\Hub\Http\ViewComposers\MarketingSuite\Partials;

use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Hub\Services\Navigation\NavigationService;
use Illuminate\View\View;

class SideMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('MarketingSuite\SideMenu');

        $view->with('navItems', $navItems);
    }
}
