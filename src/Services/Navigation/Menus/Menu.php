<?php

namespace GetCandy\Hub\Services\Navigation\Menus;

use Event;
use GetCandy\Hub\Services\Navigation\NavItem;

abstract class Menu
{
    protected $navItems = [];
    protected $view = '';
    protected $viewData = [];

    public function render()
    {
        $this->viewData['navItems'] = $this->navItems;
        return view($this->view, $this->viewData);
    }

    public function addItem(NavItem $navItem, $parentItemIndex = null)
    {
        if ($parentItemIndex !== null) {
            $this->navItems[$parentItemIndex]->addSubItem($navItem);
        } else {
            $this->navItems[] = $navItem;
        }
    }
}
