@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Nouvelle Catégorie')
@section('page-subtitle', 'Ajouter une catégorie de produits')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="data-card animate-in">
            <div class="card-header">
                <h5><i class="bi bi-tag me-2"></i>Détails de la catégorie</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Ex: Farine, Levure...">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Description optionnelle...">{{ old('description') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-lg me-1"></i>Créer la catégorie
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
