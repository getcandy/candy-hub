<?php

namespace GetCandy\Http\ViewComposers\Partials;

use GetCandy\Http\ViewComposers\BaseComposer;
use GetCandy\Cms\Services\Navigation\NavigationService;
use Illuminate\View\View;

class TopMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('TopMenu');

        $view->with('navItems', $navItems);
    }
}
