<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyTotalController extends Controller
{
    public function index(Request $request)
    {
        $depotId = auth()->user()->depot_id;
        $date = $request->input('date', Carbon::today()->toDateString());


        $deliveries = Delivery::where('depot_id', $depotId)
            ->whereDate('delivery_date', $date)
            ->whereIn('status', ['livrer', 'completed'])
            ->with(['livreur', 'items.product'])
            ->get();


            $livreurBreakdown = [];
        foreach ($deliveries as $delivery) {
            $livreurId = $delivery->livreur_id;
            $livreurName = $delivery->livreur->name ?? 'Livreur Inconnu';

            if (!isset($livreurBreakdown[$livreurId])) {
                $livreurBreakdown[$livreurId] = [
                    'name' => $livreurName,
                    'delivery_count' => 0,
                    'products' => [],
                    'total_ttc' => 0
                ];
            }

            $livreurBreakdown[$livreurId]['delivery_count']++;
            $livreurBreakdown[$livreurId]['total_ttc'] += $delivery->total_ttc;

            foreach ($delivery->items as $item) {
                $productId = $item->product_id;
                if (!isset($livreurBreakdown[$livreurId]['products'][$productId])) {
                    $livreurBreakdown[$livreurId]['products'][$productId] = [
                        'name' => $item->product->name,
                        'sku' => $item->product->sku,
                        'unit' => $item->product->unit,
                        'quantity' => 0,
                        'total_ht' => 0,
                        'total_ttc' => 0
                    ];
                }

                $livreurBreakdown[$livreurId]['products'][$productId]['quantity'] += $item->qty_delivered;
                $livreurBreakdown[$livreurId]['products'][$productId]['total_ht'] += $item->total_ht;
                $livreurBreakdown[$livreurId]['products'][$productId]['total_ttc'] += $item->total_ttc;
            }
        }


        $totals = DeliveryItem::query()
            ->join('deliveries', 'delivery_items.delivery_id', '=', 'deliveries.id')
            ->join('products', 'delivery_items.product_id', '=', 'products.id')
            ->where('deliveries.depot_id', $depotId)
            ->whereDate('deliveries.delivery_date', $date)
            ->whereIn('deliveries.status', ['livrer', 'completed'])
            ->select(
                'products.name as product_name',
                'products.sku as product_sku',
                'products.unit as product_unit',
                DB::raw('SUM(delivery_items.qty_delivered) as total_quantity'),
                DB::raw('SUM(delivery_items.total_ht) as total_ht'),
                DB::raw('SUM(delivery_items.total_ttc) as total_ttc')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.unit')
            ->get();

        $summary = [
            'total_deliveries' => $deliveries->count(),
            'total_items' => $totals->sum('total_quantity'),
            'total_value_ttc' => $totals->sum('total_ttc'),
        ];

        return view('depositaire.daily-totals', compact('livreurBreakdown', 'totals', 'date', 'summary'));
    }
}
