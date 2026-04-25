<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par produit ou référence...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-3 text-md-end text-start mt-2 mt-md-0">
                    <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $stocks->total() }} produit(s) en stock
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
                            <th>Produit <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('product_name')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th>Dépôt</th>
                            <th class="text-center">Quantité <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('quantity')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <span class="fw-bold d-block">{{ $stock->product->name }}</span>
                                        <small class="text-muted">REF: {{ $stock->product->sku ?? '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $stock->depot->name }}</td>
                            <td class="text-center">
                                @php
                                    $qty = $stock->quantity;
                                    $qtyClass = $qty <= 10 ? 'danger' : ($qty <= 50 ? 'warning' : 'success');
                                @endphp
                                <span class="badge bg-{{ $qtyClass }} bg-opacity-10 text-{{ $qtyClass }} py-1 px-2 border border-{{ $qtyClass }} border-opacity-25 font-monospace">
                                    {{ number_format($qty, 0) }} {{ $stock->product->unit }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('depositaire.products.show', $stock->product_id) }}" class="btn btn-outline-custom btn-sm" title="Voir détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Aucun stock trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $stocks->links() }}</div>
</div>
