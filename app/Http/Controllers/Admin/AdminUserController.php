<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Depot;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with(['depot', 'region'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $depots = Depot::all();
        $regions = Region::all();
        $roles = ['admin', 'commercial', 'depositaire', 'livreur'];
        return view('admin.users.create', compact('depots', 'regions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:admin,commercial,depositaire,livreur',
            'depot_id' => 'nullable|exists:depots,id',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'depot_id' => $request->role === 'depositaire' || $request->role === 'livreur' ? $request->depot_id : null,
            'region_id' => $request->role === 'commercial' ? $request->region_id : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $depots = Depot::all();
        $regions = Region::all();
        $roles = ['admin', 'commercial', 'depositaire', 'livreur'];
        return view('admin.users.edit', compact('user', 'depots', 'regions', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,commercial,depositaire,livreur',
            'depot_id' => 'nullable|exists:depots,id',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        $data = $request->only('name', 'email', 'role');
        $data['depot_id'] = $request->role === 'depositaire' || $request->role === 'livreur' ? $request->depot_id : null;
        $data['region_id'] = $request->role === 'commercial' ? $request->region_id : null;

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }
}

