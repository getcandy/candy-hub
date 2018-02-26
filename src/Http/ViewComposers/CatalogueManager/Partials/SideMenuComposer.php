<?php

namespace GetCandy\Hub\Http\ViewComposers\CatalogueManager\Partials;

use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Hub\Services\Navigation\NavigationService;
use Illuminate\View\View;

class SideMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('CatalogueManager\SideMenu');

        $view->with('navItems', $navItems);
    }
}
