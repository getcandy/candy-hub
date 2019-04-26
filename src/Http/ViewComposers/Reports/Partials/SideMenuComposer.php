<?php

namespace GetCandy\Hub\Http\ViewComposers\Reports\Partials;

use Illuminate\View\View;
use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Hub\Services\Navigation\NavigationService;

class SideMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('Reports\SideMenu');
        $view->with('navItems', $navItems);
    }
}
