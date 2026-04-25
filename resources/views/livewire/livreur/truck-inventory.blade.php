<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher un produit...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="categoryId" class="form-select form-select-sm">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 ">
                    <button wire:click="resetFilters" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-repeat"></i>Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->truck)
    <div class="alert alert-warning border border-warning border-opacity-25 bg-warning bg-opacity-10 text-warning px-4 py-3 rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <h6 class="alert-heading mb-1 d-flex align-items-center fw-bold">Aucun Camion Assigné</h6>
                <p class="mb-0 small opacity-75">Veuillez contacter l'administrateur pour vous assigner un véhicule.</p>
            </div>
        </div>
    </div>
    @else
    <div class="data-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produit <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('product_name')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>SKU <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('sku')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-center">Qté <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('quantity')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-center">Dernière MaJ <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('updated_at')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                        <tr>
                            <td>
                                <div class="fw-bold small">{{ $stock->product->name }}</div>
                                <small class="text-muted x-small text-uppercase">{{ $stock->product->category->name }}</small>
                            </td>
                            <td><small class="bg-secondary bg-opacity-10 text-muted px-2 py-1 rounded small border border-secondary border-opacity-10">{{ $stock->product->sku }}</small></td>
                            <td class="text-center">
                                <span class="badge {{ $stock->quantity > 20 ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $stock->quantity > 20 ? 'success' : 'warning' }} border border-{{ $stock->quantity > 20 ? 'success' : 'warning' }} border-opacity-25 px-3 py-1 fw-bold fs-6">
                                    {{ $stock->quantity }}
                                </span>
                            </td>
                            <td class="text-center text-muted small"><small>{{ $stock->updated_at->format('d/m/Y H:i') }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small">
                                <i class="bi bi-box-seam fs-2 mb-2 d-block"></i>
                                Votre camion est actuellement vide.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 px-3">
        {{ $stocks->links() }}
    </div>
    @endif
</div>
