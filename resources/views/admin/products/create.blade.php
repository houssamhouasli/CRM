@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Nouveau Produit')
@section('page-subtitle', 'Ajouter un nouveau produit au catalogue')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-box-seam me-2"></i>Créer un produit</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du produit</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Référence SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Prix HT (MAD)</label>
                            <input type="number" step="0.01" name="price_ht" class="form-control" value="{{ old('price_ht') }}" required min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">TVA (%)</label>
                            <input type="number" step="1" name="tva_rate" class="form-control" value="{{ old('tva_rate', 20) }}" required min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight') }}" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unité</label>
                            <select name="unit" class="form-select" required>
                                @foreach(['kg', 'g', 'unité', 'carton', 'palette'] as $unit)
                                <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-tag text-danger me-2"></i>Promotion (Optionnel)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Type de Promotion</label>
                                <select name="promo_type" class="form-select border-color bg-transparent">
                                    <option value="" class="text-dark">Aucune</option>
                                    <option value="percentage" class="text-dark" {{ old('promo_type') == 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                    <option value="fixed" class="text-dark" {{ old('promo_type') == 'fixed' ? 'selected' : '' }}>Prix Fixe (MAD)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Valeur Promo</label>
                                <input type="number" step="0.01" name="promo_value" class="form-control border-color bg-transparent" value="{{ old('promo_value', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Quantité Minimum</label>
                                <input type="number" name="promo_min_qty" class="form-control border-color bg-transparent" value="{{ old('promo_min_qty', 1) }}">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label text-secondary small text-uppercase fw-bold"><i class="bi bi-calendar-event text-primary me-1"></i>Date de début</label>
                                <input type="datetime-local" name="promo_start_date" class="form-control" value="{{ old('promo_start_date') }}">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label text-secondary small text-uppercase fw-bold"><i class="bi bi-calendar-event text-primary me-1"></i>Date de fin</label>
                                <input type="datetime-local" name="promo_end_date" class="form-control" value="{{ old('promo_end_date') }}">
                                </div>
                            </div>

                    <div class="mb-3">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Le stock est géré dynamiquement selon les dépôts et camions.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Créer</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
