<div>
    <div class="data-card mb-3 animate-in"> 
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small px-1">Recherche</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Nom ou SKU...">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small px-1">Catégorie</label>
                    <select wire:model.live="category_id" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-muted small px-1">Prix Min</label>
                    <input type="number" wire:model.live.debounce.500ms="price_min" class="form-control form-control-sm" placeholder="0" step="0.01">
                </div>
                <div class="col-md-1">
                    <label class="form-label text-muted small px-1">Prix Max</label>
                    <input type="number" wire:model.live.debounce.500ms="price_max" class="form-control form-control-sm" placeholder="Max" step="0.01">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button wire:click="resetFilters" class="btn btn-outline-primary "><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                    </div>
                </div>
            </div>
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

    <div class="data-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-body p-0">
            <div class="table-responsive position-relative">
                <div wire:loading class="position-absolute w-100 h-100" style="background: rgba(255,255,255,0.4); z-index: 10; top:0; left:0; backdrop-filter: blur(1px);"></div>
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>SKU <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('sku')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Produit <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Catégorie <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('category_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Prix (HT) <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('price_ht')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>TVA <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('tva_rate')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Unité</th>
                            <th>Poids</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td><span class="text-muted">{{ $product->sku ?? '—' }}</span></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $product->category->name ?? '—' }}</span></td>
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
                                <td>
                                    <span class="badge bg-info bg-opacity-25" style="color: #3498db;">{{ number_format($product->tva_rate, 0) }}%</span>
                                </td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ $product->weight ? $product->weight . ' kg' : '—' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmez-vous la suppression de ce produit ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="color:var(--danger);"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-box fs-2 d-block mb-2"></i>Aucun produit trouvé</td></tr>
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
