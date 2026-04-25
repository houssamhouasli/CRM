<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Product; 

class DashboardController extends Controller
{
    public function index()
    {
        $depotId = auth()->user()->depot_id;
        
        $totalOrders = Order::where('status', 'pending')->count();

        $totalDeliveries = Delivery::where('depot_id', $depotId)->count();
        
        $lowStockProducts = Product::whereHas('depotStocks', function($q) use ($depotId) {
            $q->where('depot_id', $depotId)->where('quantity', '<', 50);
        })->get();

        $recentDeliveries = Delivery::with(['order.client', 'livreur'])
            ->where('depot_id', $depotId)
            ->latest()
            ->take(5)
            ->get();

        return view('depositaire.dashboard', compact(
            'totalOrders',
            'totalDeliveries',
            'lowStockProducts',
            'recentDeliveries'
        ));
    }
}
