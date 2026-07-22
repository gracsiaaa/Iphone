<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalSales' => Order::whereIn('status', [OrderStatus::PAID->value, OrderStatus::COMPLETED->value])->sum('total'),
            'todayOrders' => Order::whereDate('created_at', today())->count(),
            'waitingPayments' => Order::where('status', OrderStatus::WAITING_VERIFICATION->value)->count(),
            'activeProducts' => Product::active()->count(),
            'lowStockProducts' => Product::active()->where('stock', '<=', 3)->orderBy('stock')->take(8)->get(),
            'resellerCount' => User::where('role', 'user')->count(),
            'recentOrders' => Order::with('user')->latest()->take(8)->get(),
            'articleCount' => Article::count(),
        ]);
    }
}
