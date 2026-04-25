<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="Rechercher par Nom, Email, Téléphone ou ID...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="region_id" class="form-select form-select-sm">
                        <option value="">Toutes les régions</option>
                        @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-4 text-md-end text-start mt-2 mt-md-0">
                    <div wire:loading class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span wire:loading.remove style="font-size: 0.8rem; color: var(--text-secondary);">
                        {{ $clients->total() }} client(s)
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
                            <th>Entreprise <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('company_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Région <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('region_name')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Email <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('email')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Téléphone <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('phone')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th>Total TTC Livraisons Livrées <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('total_ttc')"><i class="bi bi-arrow-down-up ms-3"></i></button></th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr class="align-middle">
                                <td>{{ $client->id }}</td>
                                <td><strong>{{ $client->company_name }}</strong></td>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ $client->region->name ?? '—' }}</span></td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone ?? '—' }}</td>
                                <td><span class="fw-bold">{{ number_format($client->deliveries_sum_total_ttc ?? 0, 2, ',', ' ') }} MAD</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmez-vous la suppression de ce client ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="color:var(--danger);"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5 text-center">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    Aucun client trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3 d-flex justify-content-center">
        {{ $clients->links() }}
    </div>
</div>
