<?php

namespace GetCandy\Cms\Services\Navigation;

class NavItem
{
    protected $title;
    protected $route;
    protected $subItems;

    public function __construct($title, $route, $subItems = [])
    {
        $this->title = $title;
        $this->route = $route;
        $this->subItems = $subItems;
    }

    public function getTitle()
    {
        return trans($this->title);
    }

    public function getUrl()
    {
        return route($this->route);
    }

    public function getSubItems()
    {
        return $this->subItems;
    }

    public function addSubItem(NavItem $subItem)
    {
        $this->subItems[] = $subItem;
    }
}
