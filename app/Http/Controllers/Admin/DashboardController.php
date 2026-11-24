<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('admin.dashboard', [
            'admin' => $request->user(),
            'metrics' => [
                'products' => Product::count(),
                'customers' => Customer::count(),
                'orders' => Order::count(),
                'revenue' => Order::where('status', 'delivered')->sum('total'),
                'low_stock' => Product::where('status', 'active')->where('quantity', '<=', 5)->count(),
            ],
            'recentOrders' => Order::with('customer')->latest()->limit(6)->get(),
            'lowStockProducts' => Product::where('status', 'active')
                ->where('quantity', '<=', 5)
                ->orderBy('quantity')
                ->limit(5)
                ->get(),
        ]);
    }
}
