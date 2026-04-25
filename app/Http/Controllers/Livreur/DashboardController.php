<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\ReturnModel;
use App\Models\TruckStock;
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('truck.stocks.product');
        $truck = $user->truck;

        $deliveriesCount = Delivery::where('livreur_id', $user->id)->count();
        $pendingCount = Delivery::where('livreur_id', $user->id)->where('status', 'pending')->count();
        $completedCount = Delivery::where('livreur_id', $user->id)->where('status', 'Livrer')->count();

        $returnsCount = ReturnModel::where('livreur_id', $user->id)->count();
        $pendingReturnsCount = ReturnModel::where('livreur_id', $user->id)->where('status', 'pending')->count();

        $pendingDeliveries = Delivery::with(['order.client', 'depot'])
            ->where('livreur_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $truckStocks = collect();
        if ($truck) {
            $truckStocks = TruckStock::with('product')
                ->where('truck_id', $truck->id)
                ->orderBy('quantity', 'desc')
                ->take(5)
                ->get();
        }

        return view('livreur.dashboard', compact(
            'deliveriesCount',
            'pendingCount',
            'completedCount',
            'pendingDeliveries',
            'truckStocks',
            'returnsCount',
            'pendingReturnsCount'
        ));
    }
}
