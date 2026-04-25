<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="form-control border-start-0 ps-0 form-control-sm"
                            placeholder="Rechercher par ID commande ou par client...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="livrer">Livrée</option>
                        <option value="annuler">Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary w-100"><i
                            class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-4 text-md-end text-start mt-2 mt-md-0">
                    <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $orders->total() }} commande(s)
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="data-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-body p-0">
            <div class="table-responsive position-relative">
                <div wire:loading class="position-absolute w-100 h-100"
                    style="background: rgba(255,255,255,0.4); z-index: 10; top:0; left:0; backdrop-filter: blur(1px);">
                </div>
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-person me-1"></i>Client <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client_name')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-geo-alt me-1"></i>Région <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('region_name')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-calendar-check me-1"></i>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('order_date')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-info-circle me-1"></i>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th class="text-end"><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->client->company_name }}</td>
                                <td>
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2">
                                        {{ $order->client->region->name ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('depositaire.orders.show', $order) }}"
                                        class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i>
                                        {{ $order->status === 'livrer' ? 'Voir' : 'Gérer' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5"><i
                                        class="bi bi-card-list fs-2 d-block mb-2"></i>Aucune commande trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $orders->links() }}</div>
</div>