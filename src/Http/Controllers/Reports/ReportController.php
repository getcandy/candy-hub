<?php

namespace GetCandy\Hub\Http\Controllers\Reports;

use GetCandy\Hub\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getIndex()
    {
        return view('hub::reports.sales-report');
    }

    public function getShipping()
    {
        return view('hub::reports.shipping.index');
    }

    public function productAttributes()
    {
        return view('hub::reports.products.attributes');
    }
}
