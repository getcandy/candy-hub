<?php

namespace GetCandy\Http\ViewComposers\CatalogueManager\Partials;

use GetCandy\Http\ViewComposers\BaseComposer;
use GetCandy\Cms\Services\Navigation\NavigationService;
use Illuminate\View\View;

class SideMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('CatalogueManager\SideMenu');

        $view->with('navItems', $navItems);
    }
}
