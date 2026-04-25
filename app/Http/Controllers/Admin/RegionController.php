<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request; 

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::with(['clients', 'users'])->get();
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:regions,code',
        ]);

        Region::create($request->only('name', 'code'));

        return redirect()->route('admin.regions.index')->with('success', 'Région créée avec succès.');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:regions,code,' . $region->id,
        ]);

        $region->update($request->only('name', 'code'));

        return redirect()->route('admin.regions.index')->with('success', 'Région mise à jour.');
    }

    public function destroy(Region $region)
    {
        if ($region->clients()->count() > 0 or $region->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des clients ou des utilisateurs sont associés à cette région.');
        }

        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Région supprimée.');
    }
}
