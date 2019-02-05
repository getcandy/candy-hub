<?php

namespace GetCandy\Hub\Services\Navigation;

class NavItem
{
    protected $title;
    protected $current;
    protected $route;
    protected $subItems;
    protected $icon;

    public function __construct($title, $route, $activePage = null, $subItems = [])
    {
        $this->title = $title;
        $this->route = $route;
        $this->current = $activePage == request()->segment(3);
        $this->subItems = $subItems;
    }

    public function getTitle()
    {
        return trans($this->title);
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function getUrl()
    {
        return $this->route;
    }

    public function getSubItems()
    {
        return $this->subItems;
    }

    public function addSubItem(self $subItem)
    {
        $this->subItems[] = $subItem;
    }
}
