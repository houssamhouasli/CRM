@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Catégories')
@section('page-subtitle', 'Gestion des catégories de produits')

@section('topbar-actions')
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouvelle Catégorie</a>
@endsection

@section('content')
<div class="data-card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th style="width: 150px;">Produits Liés</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="text-muted">#{{ $cat->id }}</td>
                        <td><strong>{{ $cat->name }}</strong></td>
                        <td class="text-muted small">{{ Str::limit($cat->description ?: '—', 50) }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $cat->products->count() }} produits</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.show', $cat) }}" class="btn btn-outline-custom btn-sm" title="Voir les produits">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-outline-custom btn-sm" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="color:var(--danger);" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-tag d-block fs-1 mb-2 opacity-25"></i>
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
