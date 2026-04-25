<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher un retour...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="validated">Validés</option>
                        <option value="rejected">Rejetés</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary">
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
                            <th>N° Retour <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Livraison <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('delivery_id')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Client</th>
                            <th class="text-center">Articles</th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('created_at')"><i class="bi bi-arrow-down-up ms-2"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $return->id }}</td>
                            <td>#{{ $return->delivery->id }}</td>
                            <td>
                                <div class="fw-bold small">{{ $return->delivery->order->client->company_name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-1 px-2">
                                    {{ $return->returnItems->count() }}
                                </span>
                            </td>
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
                            <td><small>{{ $return->created_at->format('d/m/Y H:i') }}</small></td>
                            <td class="text-end">
                                <a href="{{ route('livreur.returns.show', $return) }}" class="btn btn-outline-custom btn-sm" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Aucun retour trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 px-3">
        {{ $returns->links() }}
    </div>
</div>
