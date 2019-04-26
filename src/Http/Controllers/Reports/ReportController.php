<?php

namespace GetCandy\Hub\Http\Controllers\Reports;

use GetCandy\Hub\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getIndex()
    {
        return view('hub::reports.sales-report');
    }
}
