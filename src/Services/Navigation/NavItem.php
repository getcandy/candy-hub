<?php

namespace GetCandy\Hub\Services\Navigation;

class NavItem
{
    protected $title;
    protected $route;
    protected $subItems;
    protected $icon;

    public function __construct($title, $route, $subItems = [])
    {
        $this->title = $title;
        $this->route = $route;
        $this->subItems = $subItems;
    }

    public function getTitle()
    {
        return trans('hub::'.$this->title);
    }

    public function getUrl()
    {
        return $this->route;
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
