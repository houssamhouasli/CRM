@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Utilisateurs')
@section('page-subtitle', 'Gestion des utilisateurs') 

@section('topbar-actions')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouvel Utilisateur</a>
@endsection

@section('content')
<div class="data-card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Email</th> 
                        <th>Rôle</th>
                        <th>Association</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @if(($user->role === 'depositaire' or $user->role === 'livreur') && $user->depot)
                                <span class="badge bg-warning bg-opacity-25" style="color: #f39c12;">Dépôt: {{ $user->depot->name }}</span>
                            @elseif($user->role === 'commercial' && $user->region)
                                <span class="badge bg-primary bg-opacity-25" style="color: #3b82f6;">Région: {{ $user->region->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="color: var(--danger);"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucun utilisateur trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
