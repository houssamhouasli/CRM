<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Region;
use \App\Models\Delivery;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalRevenue = Delivery::where('status', 'livrer')->sum('total_ttc');
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'livrer')->count();
        $canceledOrders = Order::where('status', 'annuler')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();

        $recentOrders = Order::with('client')->where('type', 'sale')->latest('order_date')->take(10)->get(); 

        $topProducts = Product::with('category')
            ->withSum('deliveryItems as total_sold', 'qty_delivered', function($query) {
                $query->whereHas('delivery', function($q) {
                    $q->where('status', 'livrer');
                });
            })
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topSold = $topProducts->max('total_sold') ?: 1;

        $topProducts->each(function ($product) use ($topSold) {
            $product->sales_percentage = ($product->total_sold / $topSold) * 100;
        });

        $currentMonth = now()->month;

        $regions = Region::withCount('clients')->get()->map(function ($region) use ($currentMonth) {
            $region->monthly_revenue =Delivery::whereHas('order.client', function ($q) use ($region) {
                $q->where('region_id', $region->id);
            })
                ->where('status', 'livrer')
                ->whereMonth('delivery_date', $currentMonth)
                ->sum('total_ttc');

            $region->total_sales = Delivery::whereHas('order.client', function ($q) use ($region) {
                $q->where('region_id', $region->id);
            })
                ->where('status', 'livrer')
                ->sum('total_ttc');

            return $region;
        });

        return view('admin.dashboard', compact(
            'totalClients',
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'pendingOrders',
            'deliveredOrders',
            'canceledOrders',
            'confirmedOrders',
            'recentOrders',
            'topProducts',
            'topSold',
            'regions'
        ));
    }
}

