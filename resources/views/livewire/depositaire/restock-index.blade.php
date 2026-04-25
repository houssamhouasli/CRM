<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par ID demande ou statut...">
                    </div>
                </div>
                <div class="col-md-2 text-md-end text-start">
                    <button wire:click="resetFilters" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-3 text-md-end text-start mt-2 mt-md-0">
                    <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $orders->total() }} demande(s) de stock
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
                            <th><i class="bi bi-hash me-1"></i>Demande  <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-calendar-event me-1"></i>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('order_date')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th><i class="bi bi-info-circle me-1"></i>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th class="text-end"><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                    ][$order->status] ?? 'secondary';
                                    
                                    $statusLabel = [
                                        'pending' => 'En attente',
                                        'confirmed' => 'Confirmée',
                                        'delivered' => 'Expédiée',
                                        'cancelled' => 'Annulée',
                                    ][$order->status] ?? $order->status;
                                @endphp
                                <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} py-1 px-2 border border-{{ $statusClass }} border-opacity-25 small">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('depositaire.restock.show', $order) }}" class="btn btn-sm btn-outline-custom me-2"><i class="bi bi-eye"></i> Détails</a>
                                @if($order->status === 'confirmed')
                                    <form action="{{ route('depositaire.restock.receive', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmer la réception physique de ces produits ? Votre stock sera mis à jour.')">
                                            <i class="bi bi-check-circle"></i> Recevoir
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-card-list fs-2 d-block mb-2"></i>Aucune demande de stock effectuée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
