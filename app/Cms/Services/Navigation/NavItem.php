<?php

namespace GetCandy\Cms\Services\Navigation;

class NavItem
{
    protected $title;
    protected $route;
    protected $subItems;
    protected $icon;

    public function __construct($title, $route, $subItems = [], $icon = null)
    {
        $this->title = $title;
        $this->route = $route;
        $this->subItems = $subItems;
        $this->icon = $icon;
    }

    public function getTitle()
    {
        return trans($this->title);
    }

    public function getUrl()
    {
        return $this->route;
    }

    public function getIcon()
    {
        return $this->icon;
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
