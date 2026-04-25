@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', $category->name)
@section('page-subtitle', 'Détails de la catégorie et produits associés')

@section('topbar-actions')
<a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-pencil me-1"></i>Modifier</a>
<a href="{{ route('admin.categories.index') }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="data-card animate-in mb-3">
            <div class="card-header">
                <h5><i class="bi bi-tag me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-black fw-bold d-block mb-1 fs-5">Nom de la catégorie</label>
                    <span class="fw-semibold">{{ $category->name }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-black fw-bold d-block mb-1 fs-5">Description</label>
                    <p class="mb-0 text-muted">{{ $category->description ?: 'Aucune description disponible.' }}</p>
                </div>
                <hr class="my-3 opacity-10">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total produits</span>
                    <span class="badge bg-primary fs-6">{{ $category->products->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header">
                <h5><i class="bi bi-box-seam me-2"></i>Produits dans cette catégorie</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Produit</th>
                                <th>Prix (HT)</th>
                                <th>TVA</th>
                                <th>Unité</th>
                                <th>Poids</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td class="text-muted">#{{ $product->id }}</td>
                                <td><span class="text-muted">{{ $product->sku ?? '—' }}</span></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>
                                    @if($product->isPromoActive())
                                        <div class="d-flex flex-column">
                                            <span class="text-decoration-line-through text-muted small">{{ number_format($product->price_ht, 2, ',', ' ') }} MAD</span>
                                            @if($product->promo_type === 'percentage')
                                                <strong class="text-danger">{{ number_format($product->price_ht * (1 - $product->promo_value/100), 2, ',', ' ') }} MAD</strong>
                                                <span class="badge bg-danger mt-1" style="width:fit-content">-{{ number_format($product->promo_value, 0) }}%</span>
                                            @else
                                                <strong class="text-danger">{{ number_format($product->price_ht - $product->promo_value, 2, ',', ' ') }} MAD</strong>
                                                <span class="badge bg-danger mt-1" style="width:fit-content">-{{ number_format($product->promo_value, 2) }} MAD</span>
                                            @endif
                                            @if($product->promo_min_qty > 1)
                                                <small class="text-warning mt-1"><i class="bi bi-info-circle me-1"></i>Min: {{ $product->promo_min_qty }} unités</small>
                                            @endif
                                        </div>
                                    @else
                                        <strong>{{ number_format($product->price_ht, 2, ',', ' ') }} MAD</strong>
                                    @endif
                                </td>
                                <td><span class="badge bg-info bg-opacity-25" style="color: #3498db;">{{ number_format($product->tva_rate, 0) }}%</span></td>
                                <td>{{ $product->unit ?? 'u' }}</td>
                                <td>{{ $product->weight ? $product->weight . ' kg' : '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-custom btn-sm" title="Modifier le produit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    Aucun produit n'est encore associé à cette catégorie.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
