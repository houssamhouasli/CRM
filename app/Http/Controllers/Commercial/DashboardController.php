<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Delivery;

class DashboardController extends Controller
{ 
    public function index()
    {
        $regionId = auth()->user()->region_id;

        $totalClients = Client::where('region_id', $regionId)->count();
        $totalOrders = Order::whereHas('client', fn($q) => $q->where('region_id', $regionId))->count();
        $pendingOrders = Order::whereHas('client', fn($q) => $q->where('region_id', $regionId))
            ->where('status', 'pending')->count();
        $totalRevenue =Delivery::whereHas('order.client', fn($q) => $q->where('region_id', $regionId))
            ->where('status', 'livrer')
            ->sum('total_ttc');

        $recentOrders = Order::with('client')
            ->whereHas('client', fn($q) => $q->where('region_id', $regionId))
            ->latest('order_date')
            ->take(10)
            ->get();

        return view('commercial.dashboard', compact(
            'totalClients', 'totalOrders', 'pendingOrders',
            'totalRevenue', 'recentOrders'
        ));
    }
}
