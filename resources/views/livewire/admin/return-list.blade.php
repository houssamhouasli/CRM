<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher un retour...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="validated">Validés</option>
                        <option value="rejected">Rejetés</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise">Réinitialiser</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="data-card animate-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4"># <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Livraison</th>
                            <th>Client</th>
                            <th>Livreur</th>
                            <th>Dépôt <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('depot_id')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Date <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('created_at')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $return->id }}</td>
                            <td>#{{ $return->delivery->id }}</td>
                            <td>{{ $return->delivery->order->client->company_name }}</td>
                            <td>{{ $return->livreur->name }}</td>
                            <td>{{ $return->depot?->name ?? '-' }}</td>
                            <td>
                                @if($return->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($return->status === 'validated')
                                    <span class="badge bg-success">Validé</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                            <td class="pe-4">
                                <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-outline-primary">
                                    @if($return->status === 'pending')
                                        <i class="bi bi-check-square me-1"></i>Traiter
                                    @else
                                        <i class="bi bi-eye me-1"></i>Voir
                                    @endif
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Aucun retour trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $returns->links() }}
    </div>
</div>
