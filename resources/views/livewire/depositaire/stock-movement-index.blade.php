<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Produit, SKU, Raison...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="IN">Entrées (+)</option>
                        <option value="OUT">Sorties (-)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i>Réinitialiser</button>
                </div>
                <div class="col-md-2 text-md-end text-start mt-2 mt-md-0">
                    <span style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $movements->total() }} mouvement(s)
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
                            <th><i class="bi bi-clock-history me-1"></i>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('moved_at')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-box me-1"></i>Produit <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('product_name')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-info-circle me-1"></i>Type</th>
                            <th class="text-center"><i class="bi bi-stack me-1"></i>Quantité <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('quantity')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-card-text me-1"></i>Raison / Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $move)
                        <tr>
                            <td>
                                <small>{{ $move->moved_at ? $move->moved_at->format('d/m/Y H:i') : '—' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                        <i class="bi bi-box-seam x-small"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block small">{{ $move->product->name }}</span>
                                        <small class="text-muted x-small">REF: {{ $move->product->sku ?? '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeClass = $move->type === 'in' ? 'success' : 'danger';
                                    $typeLabel = $move->type === 'in' ? 'ENTRÉE' : 'SORTIE';
                                @endphp
                                <span class="badge bg-{{ $typeClass }} bg-opacity-10 text-{{ $typeClass }} py-1 px-2 border border-{{ $typeClass }} border-opacity-25" style="font-size: 0.65rem;">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td class="text-center fw-bold {{ $move->type === 'in' ? 'text-success' : 'text-danger' }} font-monospace">
                                {{ $move->type === 'in' ? '+' : '-' }}{{ number_format($move->quantity, 0) }}
                            </td>
                            <td><small class="text-muted x-small text-truncate d-inline-block" style="max-width: 200px;">{{ $move->reason ?? '—' }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-5 text-italic">Aucun mouvement récent enregistré.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $movements->links() }}</div>
</div>
