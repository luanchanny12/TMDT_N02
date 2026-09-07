<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        $todayOrders = Order::whereDate('created_at', $now->toDateString())->count();

        $monthOrders = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $totalProducts = Product::count();
        $totalUsers = User::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $revenueByDay = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartData[] = [
                'date' => Carbon::parse($date)->format('d/m'),
                'total' => $revenueByDay[$date] ?? 0,
            ];
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'todayOrders',
            'monthOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'chartData'
        ));
    }
}
