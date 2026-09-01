<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Region;
use \App\Models\Delivery;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $totalClients = Client::count();
        $totalProducts = Product::count();
        $totalRevenue = Delivery::where('status', 'livrer')->sum('total_ttc');

        $orderCounts = Order::selectRaw("
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'livrer' THEN 1 END) as delivered,
            COUNT(CASE WHEN status = 'annuler' THEN 1 END) as canceled,
            COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed
        ")->first();

        $totalOrders = (int) ($orderCounts->total ?? 0);
        $pendingOrders = (int) ($orderCounts->pending ?? 0);
        $deliveredOrders = (int) ($orderCounts->delivered ?? 0);
        $canceledOrders = (int) ($orderCounts->canceled ?? 0);
        $confirmedOrders = (int) ($orderCounts->confirmed ?? 0);

        $recentOrders = Order::with('client')->where('type', 'sale')->latest('order_date')->take(10)->get(); 

        $topProducts = Product::with('category')
            ->withSum(['deliveryItems as total_sold' => function($query) {
                $query->whereHas('delivery', function($q) {
                    $q->where('status', 'livrer');
                });
            }], 'qty_delivered')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topSold = $topProducts->max('total_sold') ?: 1;

        $topProducts->each(function ($product) use ($topSold) {
            $product->sales_percentage = ($product->total_sold / $topSold) * 100;
        });

        $currentMonth = now()->month;

        $monthlyRevenueByRegion = Delivery::where('deliveries.status', 'livrer')
            ->whereMonth('deliveries.delivery_date', $currentMonth)
            ->join('orders', 'deliveries.order_id', '=', 'orders.id')
            ->join('clients', 'orders.client_id', '=', 'clients.id')
            ->groupBy('clients.region_id')
            ->selectRaw('clients.region_id, SUM(deliveries.total_ttc) as total')
            ->pluck('total', 'region_id');

        $salesQuery = Delivery::where('deliveries.status', 'livrer')
            ->join('orders', 'deliveries.order_id', '=', 'orders.id')
            ->join('clients', 'orders.client_id', '=', 'clients.id');

        if ($startDate) {
            $salesQuery->whereDate('deliveries.delivery_date', '>=', $startDate);
        }
        if ($endDate) {
            $salesQuery->whereDate('deliveries.delivery_date', '<=', $endDate);
        }

        $totalSalesByRegion = $salesQuery->groupBy('clients.region_id')
            ->selectRaw('clients.region_id, SUM(deliveries.total_ttc) as total')
            ->pluck('total', 'region_id');

        $regions = Region::withCount('clients')->get()->map(function ($region) use ($monthlyRevenueByRegion, $totalSalesByRegion) {
            $region->monthly_revenue = (float) ($monthlyRevenueByRegion[$region->id] ?? 0);
            $region->total_sales = (float) ($totalSalesByRegion[$region->id] ?? 0);
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

