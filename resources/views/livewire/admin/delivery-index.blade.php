<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par ID livraison ou client...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="status" class="form-select border-color form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="livrer">Livrée</option>
                        <option value="annuler">Annulée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="depot" class="form-select border-color form-select-sm">
                        <option value="">Tous les dépôts</option>
                        @foreach($depots as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i> Réinitialiser</button>
                </div>
                <div class="col-md-2 text-md-end text-start mt-2 mt-md-0">
                    <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $deliveries->total() }} livraison(s)
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
                            <th># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-1"></i></button></th>
                            <th>Commande</th>
                            <th>Client</th>
                            <th>Livreur</th>
                            <th>Dépôt</th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('delivery_date')"><i class="bi bi-arrow-down-up ms-1"></i></button></th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-1"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                        <tr>
                            <td><strong>#{{ $delivery->id }}</strong></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">#{{ $delivery->order_id }}</span></td>
                            <td>{{ $delivery->order->client->company_name ?? '—' }}</td>
                            <td>{{ $delivery->livreur->name ?? '—' }}</td>
                            <td>{{ $delivery->depot->name ?? '—' }}</td>
                            <td>{{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') : '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $delivery->status === 'livrer' ? 'success' : ($delivery->status === 'annuler' ? 'danger' : 'warning') }}">
                                    {{ ['pending' => 'En attente', 'livrer' => 'Livrée', 'annuler' => 'Annulée'][$delivery->status] ?? $delivery->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-truck fs-2 d-block mb-2"></i>Aucune livraison trouvée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $deliveries->links() }}</div>
</div>
