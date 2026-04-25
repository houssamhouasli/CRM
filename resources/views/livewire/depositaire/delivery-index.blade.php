<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par Bon, Client ou Livreur...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-3 text-md-end text-start mt-2 mt-md-0">
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
                            <th>Bon # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Commande # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('order_id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Client <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client_name')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Livreur <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('livreur_name')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-end">Montant TTC</th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('delivery_date')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $del)
                        <tr>
                            <td><strong>#{{ $del->id }}</strong></td>
                            <td><a href="{{ route('depositaire.orders.show', $del->order_id) }}" class="text-primary text-decoration-none">#{{ $del->order_id }}</a></td>
                            <td>{{ $del->order->client->company_name ?? '—' }}</td>
                            <td><span class="fw-bold">{{ $del->livreur->name ?? '—' }}</span></td>
                            <td class="text-end fw-bold" style="color:var(--primary);">
                                {{ $del->total_ttc > 0 ? number_format($del->total_ttc, 2, ',', ' ') . ' MAD' : '—' }}
                            </td>
                            <td>{{ $del->delivery_date ? \Carbon\Carbon::parse($del->delivery_date)->format('d/m/Y') : '—' }}</td>
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
                            <td class="text-end">
                                <a href="{{ route('depositaire.deliveries.show', $del) }}" class="btn btn-outline-custom btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-truck-flatbed fs-2 d-block mb-2"></i>Aucune livraison trouvée pour votre dépôt.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $deliveries->links() }}
    </div>
</div>
