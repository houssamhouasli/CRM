<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par Retour, Client ou Livreur...">
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
                        {{ $returns->total() }} retour(s)
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
                            <th>Retour # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Commande # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('order_id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Client <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Livreur <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('livreur')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('created_at')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td><strong>#{{ $return->id }}</strong></td>
                            <td>
                                @if($return->delivery && $return->delivery->order)
                                    <a href="{{ route('depositaire.orders.show', $return->delivery->order->id) }}" class="text-primary text-decoration-none">#{{ $return->delivery->order->id }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($return->delivery && $return->delivery->order && $return->delivery->order->client)
                                    {{ $return->delivery->order->client->company_name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><span class="fw-bold">{{ $return->livreur->name ?? '—' }}</span></td>
                            <td>{{ $return->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'validated' => 'success',
                                        'rejected' => 'danger',
                                    ][$return->status] ?? 'secondary';
                                    
                                    $statusLabel = [
                                        'pending' => 'En attente',
                                        'validated' => 'Validé',
                                        'rejected' => 'Rejeté',
                                    ][$return->status] ?? $return->status;
                                @endphp
                                <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} py-1 px-2 border border-{{ $statusClass }} border-opacity-25 small">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('depositaire.returns.show', $return) }}" class="btn btn-outline-custom btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Aucun retour trouvé pour votre dépôt.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $returns->links() }}
    </div>
</div>
