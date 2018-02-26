<?php

namespace GetCandy\Hub\Http\ViewComposers\Partials;

use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Hub\Services\Navigation\NavigationService;
use Illuminate\View\View;

class TopMenuComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $navItems = NavigationService::render('TopMenu');

        $view->with('navItems', $navItems);
    }
}
