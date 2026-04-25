<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request; 

class ClientController extends Controller
{
    public function index()
    {
        return view('commercial.clients.index');
    }
    public function create()
    {
        return view('commercial.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $data = $request->only('company_name', 'email', 'phone', 'address');
        $data['region_id'] = auth()->user()->region_id;

        Client::create($data);

        return redirect()->route('commercial.clients.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        $client->load(['region', 'orders' => fn($q) => $q->latest('order_date')]);
        return view('commercial.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('commercial.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($request->only('company_name', 'email', 'phone', 'address'));

        return redirect()->route('commercial.clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {

        if ($client->orders()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des commandes sont associées à ce client.');
        }

        $client->delete();

        return redirect()->route('commercial.clients.index')->with('success', 'Client supprimé.');
    }


}
