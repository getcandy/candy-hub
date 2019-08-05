<?php

namespace GetCandy\Hub\Services\Navigation;

use Event;

class NavigationService
{
    public static function render($menuClass)
    {
        if (class_exists($menuClass)) {
            $r = new \ReflectionClass($menuClass);
        } else {
            $r = new \ReflectionClass('GetCandy\\Hub\\Services\\Navigation\\Menus\\'.$menuClass);
        }

        $menu = $r->newInstanceArgs([]);

        Event::dispatch('cms.navigation.pre_render', [$menu]);

        return $menu->render();
    }
}
