<?php

namespace GetCandy\Http\Controllers\Cms;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Categories\Models\Category;

class DashboardController extends Controller
{
    public function getIndex()
    {
        $orderCollection = Order::withoutGlobalScope('open')->withoutGlobalScope('not_expired');
        $orders = $orderCollection->count();
        $sales = $orderCollection->where('status', '=', 'complete')->sum('total');
        $taxes = $orderCollection->sum('vat');
        $baskets = Basket::count();
        $recentOrders = $orderCollection->take(8)->get();
        $products = Product::count();
        $users = User::count();
        $categories = Category::count();
        $channels = Channel::count();
        
        return view('dashboard', [
            'order_count' => $orders,
            'recent_orders' => $recentOrders,
            'sales_total' => $sales,
            'tax_total' => $taxes,
            'basket_count' => $baskets,
            'recent_orders' => $recentOrders,
            'product_count' => $products,
            'user_count' => $users,
            'category_count' => $categories,
            'channel_count' => $channels
        ]);
    }
}
