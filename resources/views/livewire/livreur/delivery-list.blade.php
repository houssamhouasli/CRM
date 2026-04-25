<div>

    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3"> 
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="ID de livraison, Nom du client...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="proposition">Proposition</option>
                        <option value="livrer">Livrée</option>
                        <option value="annuler">Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary ">
                        <i class="bi bi-arrow-clockwise"></i>Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="data-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-body p-0">
            <div class="table-responsive position-relative">
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                        <th>Bon # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        <th>Client <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client_name')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        <th class="text-end">Montant TTC <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('total_ttc')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('delivery_date')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        <th>Dépôt</th>
                        <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                        <th class="text-center">Retours</th>
                        <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $del)
                            <tr>
                            <td class="fw-bold text-primary">#{{ $del->id }}</td>
                            <td>
                            <div class="fw-bold small">{{ $del->order->client->company_name }}</div>
                            <small class="text-muted x-small">Commande #{{ $del->order_id }}</small>
                            </td>
                            <td class="text-end fw-bold" style="color:var(--primary);">
                            {{ $del->total_ttc > 0 ? number_format($del->total_ttc, 2, ',', ' ') . ' MAD' : '—' }}
                            </td>
                            <td><small>{{ $del->delivery_date ? \Carbon\Carbon::parse($del->delivery_date)->format('d/m/Y') : 'N/A' }}</small></td>
                            <td><small>{{ $del->depot->name ?? 'N/A' }}</small></td>
                            <td>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'proposition' => 'info',
                                    'livrer' => 'success',
                                    'annuler' => 'danger',
                                ][$del->status] ?? 'secondary';
                            
                                $statusLabel = [
                                    'pending' => 'En attente',
                                    'proposition' => '⚡ Proposition',
                                    'livrer' => 'Livrée',
                                    'annuler' => 'Annulée',
                                ][$del->status] ?? $del->status;
                            @endphp
                            <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} py-1 px-2 border border-{{ $statusClass }} border-opacity-25 small">
                                {{ $statusLabel }}
                            </span>
                            </td>
                            <td class="text-center">
                            @if($del->returns->count() > 0)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 py-1 px-2" style="font-size: 0.7rem;">
                                    {{ $del->returns->count() }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('livreur.deliveries.show', $del->id) }}" class="btn btn-outline-custom btn-sm" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-truck-flatbed fs-2 d-block mb-2"></i>Aucune livraison trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 px-3">
        {{ $deliveries->links() }}
    </div>
</div>
