<?php

namespace GetCandy\Http\Controllers\Cms;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Categories\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getIndex()
    {
        $orderCollection = Order::withoutGlobalScope('open')->withoutGlobalScope('not_expired');
        $orders = $orderCollection->count();

        $lastWeeksSales = $this->getSalesLastWeek();
        $thisWeeksSales = $this->getSalesThisWeek();

        $ordersLastWeek = $this->ordersLastWeek()->count();
        $ordersThisWeek = $this->ordersThisWeek()->count();

        $salesPercent = (1 - $lastWeeksSales / $thisWeeksSales) * 100;

        $baskets = Basket::count();
        $recentOrders = $orderCollection->orderBy('placed_at', 'desc')->take(8)->get();
        $products = Product::count();
        $users = User::count();
        $categories = Category::count();
        $channels = Channel::count();

        return view('dashboard', [
            'order_count' => $orders,
            'recent_orders' => $recentOrders,
            'sales_this_week' => $thisWeeksSales,
            'sales_last_week' => $lastWeeksSales,
            'orders_this_week' => $ordersThisWeek,
            'orders_last_week' => $ordersLastWeek,
            'sales_percent' => $salesPercent,
            'basket_count' => $baskets,
            'recent_orders' => $recentOrders,
            'product_count' => $products,
            'user_count' => $users,
            'category_count' => $categories,
            'channel_count' => $channels
        ]);
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

    protected function ordersLastWeek()
    {
        return Order::withoutGlobalScope('open')
            ->withoutGlobalScope('not_expired')
            ->whereNotNull('placed_at')
            ->whereBetween('placed_at', [
                Carbon::now()->startOfWeek()->subWeeks(1),
                Carbon::now()->endofWeek()->subWeeks(1)
            ]);
    }

    protected function getSalesThisWeek()
    {
        return $this->ordersThisWeek()->sum('total');
    }

    protected function getSalesLastWeek()
    {
        return $this->ordersLastWeek()->sum('total');
    }
}
