<div>
    <div class="data-card mb-3 animate-in">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-color"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0 form-control-sm" placeholder="ID, Client...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
                </div>
                <div class="col-md-3 text-md-end text-start mt-2 mt-md-0">
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
                            <th>Commande # <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('id')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th>Client <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('client')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th>Date Création <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('created_at')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th class="text-end">Montant TTC <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('total_ttc')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th>Statut <button class="btn btn-link p-0 border-0 text-secondary" wire:click="sortBy('status')"><i class="bi bi-arrow-down-up ms-2 small"></i></button></th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                        <tr>
                            <td><strong>#{{ $o->id }}</strong></td>
                            <td>{{ $o->client->company_name ?? 'Inconnu' }}</td>
                            <td>{{ $o->order_date?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="text-end fw-bold" style="color:var(--primary);">
                                {{ number_format($o->total_ttc, 2, ',', ' ') }} MAD
                            </td>
                            <td>
                                @php
                                    $col = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'livrer' => 'success',
                                        'annuler' => 'danger'
                                    ][$o->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $col }} bg-opacity-10 text-{{ $col }} py-1 px-2 border border-{{ $col }} border-opacity-25 small">{{ strtoupper($o->status) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('livreur.orders.show', $o->id) }}" class="btn btn-outline-custom btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Aucune commande trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
