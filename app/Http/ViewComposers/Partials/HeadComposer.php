<?php

namespace GetCandy\Http\ViewComposers\Partials;

use GetCandy\Http\ViewComposers\BaseComposer;
use Illuminate\View\View;

class HeadComposer extends BaseComposer
{
    public function compose(View $view)
    {
        $view->with('head_html', $this->assemble('cms.head_html'));
    }
}
