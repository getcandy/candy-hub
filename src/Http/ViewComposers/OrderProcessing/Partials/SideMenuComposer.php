<?php

namespace GetCandy\Hub\Http\ViewComposers\OrderProcessing\Partials;

use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Hub\Services\Navigation\NavigationService;
use Illuminate\View\View;

class SideMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('OrderProcessing\SideMenu');

        $view->with('navItems', $navItems);
    }
}
