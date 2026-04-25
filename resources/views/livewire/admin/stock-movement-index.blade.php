<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search_id" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Recherche par ID...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="product_id" class="form-select form-select-sm">
                        <option value="">Tous les produits</option>
                        @foreach($products as $p) 
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="IN">Entrées (IN)</option>
                        <option value="OUT">Sorties (OUT)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="row mt-2">
                    <div class="col-12 text-end">
                        <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                            {{ $movements->total() }} mouvement(s)
                        </span>
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
                            <th># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Produit <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('product_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Type <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('type')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Quantité <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('quantity')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Raison</th>
                            <th>Par <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('user_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('moved_at')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $mv)
                        <tr>
                            <td>{{ $mv->id }}</td>
                            <td><strong>{{ $mv->product->name }}</strong></td>
                            <td>
                                @if($mv->type === 'in')
                                <span class="badge bg-success bg-opacity-25" style="color:#2ecc71;"><i class="bi bi-arrow-down-circle me-1"></i>IN</span>
                                @else
                                <span class="badge bg-danger bg-opacity-25" style="color:#e74c3c;"><i class="bi bi-arrow-up-circle me-1"></i>OUT</span>
                                @endif
                            </td>
                            <td>{{ $mv->quantity }} {{ $mv->product->unit }}</td>
                            <td class="text-muted">{{ $mv->reason ?? '—' }}</td>
                            <td>{{ $mv->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($mv->moved_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-box-seam fs-2 d-block mb-2"></i>Aucun mouvement enregistré</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $movements->links() }}</div>
</div>
