<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Models\Order;


class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients.index');
    }

    public function create()
    {
        $regions = Region::all();
        return view('admin.clients.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        Client::create($request->only('company_name', 'email', 'phone', 'address', 'region_id'));

        return redirect()->route('admin.clients.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client, Request $request)
    {
        $client->load(['region']);
        $orders = $client->orders();
        if ($request->filled('status')) {
            $orders->where('status', $request->status);
        }
        
        $orders = $orders->latest('order_date')->paginate(5);

        return view('admin.clients.show', compact('client', 'orders'));
    }

    public function edit(Client $client)
    {
        $regions = Region::all();
        return view('admin.clients.edit', compact('client', 'regions'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        $client->update($request->only('company_name', 'email', 'phone', 'address', 'region_id'));

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        if ($client->orders()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des commandes sont associées à ce client.');
        }
        $client->delete();
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé.');
    }
}
