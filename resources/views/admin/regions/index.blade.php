@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Régions')
@section('page-subtitle', 'Gestion de toutes les régions')

@section('topbar-actions')
<a href="{{ route('admin.regions.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouvelle Région</a>
@endsection

@section('content')
<div class="data-card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Nom</th><th>Code</th><th>Clients</th><th>Admins</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                    <tr>
                        <td>{{ $region->id }}</td>
                        <td><strong>{{ $region->name }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $region->code }}</span></td>
                        <td>{{ $region->clients->count() }}</td>
                        <td>{{ $region->users->count() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette région ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="color: var(--danger);"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune région</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
