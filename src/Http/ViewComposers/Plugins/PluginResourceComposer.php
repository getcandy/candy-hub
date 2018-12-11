<?php

namespace GetCandy\Hub\Http\ViewComposers\Plugins;

use Illuminate\View\View;
use GetCandy\Hub\Http\ViewComposers\BaseComposer;
use GetCandy\Api\Core\Plugins\PluginManagerInterface;
use GetCandy\Hub\Services\Navigation\NavigationService;

class PluginResourceComposer
{
    protected $plugins;

    public function __construct(PluginManagerInterface $plugins)
    {
        $this->plugins = $plugins;
    }

    public function compose(View $view)
    {
        $js = [];
        $css = [];

        foreach ($this->plugins->all() as $handle => $plugin) {
            foreach ($plugin->getJsResources() as $resource) {
                $js[] = $resource;
            }
            $css[] = $plugin->getCssResources();
        }
        $view->with('plugin_scripts', $js);
    }
}
