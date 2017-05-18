<?php

namespace GetCandy\Http\ViewComposers\Partials;

use GetCandy\Http\ViewComposers\BaseComposer;
use Illuminate\View\View;

class ScriptsComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $view->with('scripts_html', $this->assemble('cms.scripts_html'));
    }
}
