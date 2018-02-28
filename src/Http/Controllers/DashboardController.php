<?php

namespace GetCandy\Hub\Http\Controllers;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getIndex()
    {
        // $orderCollection = Order::withoutGlobalScope('open')->withoutGlobalScope('not_expired');
        // $orders = $orderCollection->count();

        // $lastWeeksSales = $this->getSalesLastWeek();
        // $thisWeeksSales = $this->getSalesThisWeek();

        // $lastMonthSales = $this->ordersForDateRange(
        //     Carbon::now()->startOfMonth()->subMonth(),
        //     Carbon::now()->endOfMonth()->subMonth()
        // )->sum('total');

        // $thisMonthSales = $this->ordersForDateRange(
        //     Carbon::now()->startOfMonth(),
        //     Carbon::now()->endOfMonth()
        // )->sum('total');

        // $ordersLastWeek = $this->ordersLastWeek()->count();

        // $ordersThisMonth = $this->ordersForDateRange(
        //     Carbon::now()->startOfMonth(),
        //     Carbon::now()->endOfMonth()
        // )->count();

        // $ordersLastMonth = $this->ordersForDateRange(
        //     Carbon::now()->startOfMonth()->subMonth(),
        //     Carbon::now()->endOfMonth()->subMonth()
        // )->count();

        // $ordersThisWeek = $this->ordersThisWeek()->count();

        // $salesPercent = (1 - $lastWeeksSales / $thisWeeksSales) * 100;

        // $baskets = Basket::count();
        // $recentOrders = $orderCollection->orderBy('placed_at', 'desc')->take(8)->get();
        // $products = Product::count();
        // $users = User::count();
        // $categories = Category::count();
        // $channels = Channel::count();

        return view('hub::dashboard', [
            'basket_count' => 0,
            'category_count' => 0,
            'channel_count' => 0,
            'graph_data' => [],
            'order_count' => 0,
            'orders_last_month' => 1,
            'orders_last_week' => 2,
            'orders_this_month' => 1,
            'orders_this_week' => 1,
            'product_count' => 1,
            'recent_orders' => 1,
            'recent_orders' => 2,
            'sales_data' => [],
            'sales_last_month' => 2,
            'sales_last_week' => 2,
            'sales_percent' => 3,
            'sales_this_month' => 2,
            'sales_this_week' => 2,
            'user_count' => 2
        ]);
    }

    protected function salesData()
    {
        $data = [];

        for ($i = 1; $i < 9; $i++) {
            // Set up initial labels
            $start = Carbon::now()->startOfWeek()->subWeeks($i);
            $end = Carbon::now()->endOfWeek()->subWeeks($i);

            $prevStart = Carbon::now()->startOfWeek()->subWeeks($i + 1);
            $prevEnd = Carbon::now()->endOfWeek()->subWeeks($i + 1);

            $label = $start->format('dS') . ' to ' . $end->format('dS') . ' ' . $end->format('F Y');

            $total = $ordersData[] = $this->ordersForDateRange(
                $start,
                $end
            )->sum(\DB::RAW('total / conversion'));

            $previous = $this->ordersForDateRange(
                $prevStart,
                $prevEnd
            )->sum(\DB::RAW('total / conversion'));

            $data[$label] = [
                'total' => $total,
                'previous' => $previous,
                'diff' => $total - $previous
            ];
        }

        return $data;
    }

    public function getGraph()
    {
        $datasets = [];

        $labels = [];
        $ordersData = [];
        $salesData = [];

        for ($i=9; $i > 0; $i--) {
            // Set up initial labels
            $start = Carbon::now()->startOfWeek()->subWeeks($i);
            $end = Carbon::now()->endOfWeek()->subWeeks($i);
            $labels[] = $start->format('dS') . '/' . $end->format('dS') . ' ' . $end->format('M');

            // Set up orders dataset
            $ordersData[] = $this->ordersForDateRange(
                $start,
                $end
            )->count();

            $salesData[] = $this->ordersForDateRange(
                $start,
                $end
            )->sum(\DB::RAW('total / conversion'));
        }

        $datasets[] = [
            'label' => 'Orders',
            'backgroundColor' => '#E7028C',
            'yAxisID' => 'A',
            'borderColor' => '#E7028C',
            'data' => $ordersData,
            'fill' =>  false
        ];

        $datasets[] = [
            'label' => 'Sales',
            'backgroundColor' => '#0099e5',
            'yAxisID' => 'B',
            'borderColor' => '#0099e5',
            'data' => $salesData,
            'fill' => false
        ];

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    protected function ordersThisWeek()
    {
        return Order::withoutGlobalScope('open')
            ->withoutGlobalScope('not_expired')
            ->whereNotNull('placed_at')
            ->whereBetween('placed_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
    }

    protected function ordersForDateRange($from, $to)
    {
        return Order::withoutGlobalScope('open')
            ->withoutGlobalScope('not_expired')
            ->whereNotNull('placed_at')
            ->whereBetween('placed_at', [
                $from,
                $to
        ]);
    }

    protected function ordersLastWeek()
    {
        return $this->ordersForDateRange(
            Carbon::now()->startOfWeek()->subWeeks(1),
            Carbon::now()->endofWeek()->subWeeks(1)
        );
    }

    protected function getSalesThisWeek()
    {
        return $this->ordersThisWeek()->sum(\DB::RAW('total / conversion'));
    }

    protected function getSalesLastWeek()
    {
        return $this->ordersLastWeek()->sum(\DB::RAW('total / conversion'));
    }
}
