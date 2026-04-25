@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Modifier Catégorie')
@section('page-subtitle', 'Modification de la catégorie : ' . $category->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="data-card animate-in">
            <div class="card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Détails de la catégorie</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-arrow-repeat me-1"></i>Mettre à jour
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
