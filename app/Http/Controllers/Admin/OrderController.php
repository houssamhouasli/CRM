<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['client', 'items.product', 'deliveries.livreur', 'deliveries.depot']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,livrer,annuler',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]); 

        $clientEmail = $order->client->email ?? null;
        if ($clientEmail) {
            Mail::to($clientEmail)->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        }

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

}
