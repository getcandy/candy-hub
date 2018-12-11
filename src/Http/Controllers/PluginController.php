<?php

namespace GetCandy\Hub\Http\Controllers;

use GetCandy\Api\Core\Plugins\PluginManagerInterface;
use Illuminate\Support\Facades\Response;

class PluginController extends Controller
{
    public function resource($handle, $type, $filename, PluginManagerInterface $plugins)
    {
        // Get our plugin.
        $plugin = $plugins->get($handle);
        $filepath = $plugin->getResource($type, $filename);
        if (!$filepath ){
            abort(404);
        }
        $response = Response::make(file_get_contents($filepath), 200);
        $response->header('Content-Type', 'application/javascript');
        return $response;
    }
}
