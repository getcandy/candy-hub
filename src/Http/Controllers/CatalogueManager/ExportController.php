<?php

namespace GetCandy\Hub\Http\Controllers\CatalogueManager;

use GetCandy\Hub\Http\Requests\ExportRequest;
use GetCandy\Hub\Http\Controllers\Controller;
use GetCandy\Hub\Jobs\Products\ExportProductsJob;

class ExportController extends Controller
{
    public function export(ExportRequest $request)
    {
        ExportProductsJob::dispatch($request->email);
        return response()->json([
            'exported' => true,
        ]);
    }
}
