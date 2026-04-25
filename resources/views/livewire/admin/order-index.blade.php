<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par ID commande ou par client...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="status" class="form-select border-color form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="livrer">Livré</option>
                        <option value="annuler">Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="type" class="form-select border-color form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="sale">Ventes Clients</option> 
                        <option value="restock">Réappro Dépôt</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise">Réinitialiser</i></button>
                </div>
                <div class="col-md-2 text-md-end text-start mt-2 mt-md-0">
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
                <div wire:loading class="position-absolute w-100 h-100" style="background: rgba(255,255,255,0.4); z-index: 10; top:0; left:0; backdrop-filter: blur(1px);"></div>
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Entité <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Région/Dépôt</th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('order_date')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Type</th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                @if($order->type === 'restock')
                                    <span class="text-info fw-bold"><i class="bi bi-house-door me-1"></i>{{ $order->creator->depot->name ?? 'Dépôt Inconnu' }}</span>
                                @else
                                    {{ $order->client->company_name ?? '—' }}
                                @endif
                            </td>
                            <td>
                                @if($order->type === 'restock')
                                    <small class="text-muted">Réapprovisionnement</small>
                                @else
                                    <span class="badge bg-info bg-opacity-25" style="color:#5dade2;">{{ $order->client->region->name ?? '—' }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->type === 'restock' ? 'info' : 'primary' }} bg-opacity-10 text-{{ $order->type === 'restock' ? 'info' : 'primary' }} px-2 py-1">
                                    {{ $order->type === 'restock' ? 'Réappro' : 'Vente' }}
                                </span>
                            </td>
                            <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-card-list fs-2 d-block mb-2"></i>Aucune commande trouvée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
