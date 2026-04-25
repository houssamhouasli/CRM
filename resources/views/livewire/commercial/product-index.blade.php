<div>
    <style>
        .card-clickable:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3) !important; border-color: var(--primary-light) ; }
    </style>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher (nom, sku)...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="category_id" class="form-select form-select-sm">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <button wire:click="resetFilters" class="btn btn-outline-primary mt-4"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                    <div class="row mt-2">
                        <div class="col-12 text-end">
                            <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                                {{ $products->total() }} produit(s)
                    </span>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>

    <div class="data-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-body p-0">
            <div class="table-responsive position-relative">
                <div wire:loading class="position-absolute w-100 h-100" style="background: rgba(255,255,255,0.4); z-index: 10; top:0; left:0; backdrop-filter: blur(1px);"></div>
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th class="text-end">Prix (HT)</th>
                            <th class="text-center">TVA</th>
                            <th class="text-center">Unité</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->promo_value > 0 && (!$product->promo_start_date || $product->promo_start_date <= now()) && (!$product->promo_end_date || $product->promo_end_date >= now()))
                                                <span class="ms-2 badge bg-warning text-dark x-small animate-pulse" title="Promotion active">
                                                    <i class="bi bi-megaphone-fill me-1"></i>PROMO
                                                </span>
                                            @endif
                                    </div>
                                    <span class="text-muted small">{{ $product->sku ?? '—' }}</span>
                                    
                                </td>
                                <td><span class="badge bg-secondary opacity-75">{{ $product->category->name ?? '—' }}</span></td>
                                <td class="text-end fw-bold">{{ number_format($product->price_ht, 2, ',', ' ') }} MAD</td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">{{ number_format($product->tva_rate, 0) }}%</span>
                                </td>
                                <td class="text-center">{{ $product->unit ?? 'u' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('commercial.products.show', $product) }}" class="btn btn-outline-custom btn-sm py-1 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-box fs-2 d-block mb-2"></i>Aucun produit trouvé</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $products->links() }}</div>
</div>
