<?php

namespace GetCandy\Hub\Services\Navigation\Menus;

use GetCandy\Hub\Services\Navigation\NavItem;

abstract class Menu
{
    protected $navItems = [];
    protected $view = '';
    protected $viewData = [];
    protected $area;

    public function render()
    {
        $this->viewData['navItems'] = $this->navItems;

        return view($this->view, $this->viewData);
    }

    public function for($area)
    {
        return $this->area == $area;
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
