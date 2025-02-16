<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\LiveChat;
use Carbon\Carbon;

class homeController extends Controller
{
    public function index()
    {
        // إحصائيات المستخدمين
        $usersCount = User::count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // إحصائيات الطلبات
        $ordersCount = Order::count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $ordersRevenue = Order::where('status', 'delivered')->count();

        // إحصائيات المنتجات
        $productsCount = Product::count();
        $lowStockProducts = Product::where('quantity', '<', 10)->count();

        // إحصائيات الدردشة
        $unreadMessages = LiveChat::where('status', 'unread')->count();

        // تحليل المبيعات
        $salesData = $this->getSalesData();

        return view('dashboard.index', compact(
            'usersCount',
            'newUsers',
            'ordersCount',
            'recentOrders',
            'ordersRevenue',
            'productsCount',
            'lowStockProducts',
            'unreadMessages',
            'salesData'
        ));
    }

    private function getSalesData()
    {
        $sales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales[] = [
                'date' => $date->format('Y-m-d'),
                'total' => Order::whereDate('created_at', $date)
                    ->where('status', 'delivered')
                    ->count(),
            ];
        }
        return $sales;
    }
}
