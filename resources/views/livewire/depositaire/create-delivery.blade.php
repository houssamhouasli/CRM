<div class="row g-4">
    <div class="col-lg-4">
        <div class="data-card animate-in mb-3">
            <div class="card-header border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Commande #{{ $order->id }}</h5>
                <span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block fw-bold">Client</label>
                    <div class="h5 mb-0 fw-bold text-primary">{{ $order->client->company_name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block fw-bold">Région</label>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                        <i class="bi bi-geo-alt me-1"></i>{{ $order->client->region->name ?? 'N/A' }}
                    </span>
                </div>
                <div class="mb-0">
                    <label class="text-muted small d-block fw-bold">Date Commande</label>
                    <span class="text-light"><i class="bi bi-calendar me-1"></i>{{ $order->order_date->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>


        <div class="data-card animate-in" style="animation-delay: 0.1s;">
            <div class="card-header border-bottom border-secondary border-opacity-25">
                <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-repeat me-2 text-warning"></i>Proposer des Substitutions</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Proposez des produits alternatifs si le stock commandé n'est pas disponible. 
                    Le client devra accepter ou refuser via le livreur.
                </p>


                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Produit à proposer</label>
                    <select wire:model.live="newSubProductId" class="form-select form-select-sm @error('newSubProductId') is-invalid @enderror">
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($availableProducts as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} (Stock: {{ $p->depotStocks->first()->quantity ?? 0 }}) — {{ number_format($p->price_ht, 2, ',', ' ') }} MAD
                                @if($p->isPromoActive()) 📢 promo @endif
                            </option>
                        @endforeach
                    </select>
                    @error('newSubProductId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror


                    @if($newSubProductId)
                        @php
                            $selectedSubProduct = $availableProducts->firstWhere('id', $newSubProductId);
                        @endphp
                        @if($selectedSubProduct && $selectedSubProduct->isPromoActive())
                            <div class="mt-2 p-2 rounded animate-in" style="background: rgba(13, 202, 240, 0.08); border: 1px solid rgba(13, 202, 240, 0.2);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gift text-info me-2"></i>
                                    <div class="small">
                                        <span class="fw-bold text-info">Promotion active :</span>
                                        <span class="text-warning">
                                            @if($selectedSubProduct->promo_type === 'percentage')
                                                -{{ number_format($selectedSubProduct->promo_value, 0) }}%
                                            @else
                                                -{{ number_format($selectedSubProduct->promo_value, 2) }} MAD
                                            @endif
                                            @if($selectedSubProduct->promo_min_qty > 1)
                                                (Dès {{ $selectedSubProduct->promo_min_qty }} unités)
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Quantité</label>
                    <input type="number" wire:model="newSubQty" class="form-control form-control-sm @error('newSubQty') is-invalid @enderror" min="1" value="1">
                    @error('newSubQty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="button" wire:click="addSubstitution" class="btn btn-warning btn-sm w-100 fw-bold">
                    <i class="bi bi-plus-circle me-1"></i>Ajouter la substitution
                </button>


                @if(count($substitutions) > 0)
                    <div class="mt-3 border-top border-secondary border-opacity-25 pt-3">
                        <h6 class="fw-bold small text-warning mb-2">
                            <i class="bi bi-arrow-repeat me-1"></i>Produits proposés ({{ count($substitutions) }})
                        </h6>
                        @foreach($substitutions as $idx => $sub)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" 
                                 style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.2);">
                                <div class="small">
                                    <div class="fw-bold">{{ $sub['product_name'] }}</div>
                                    <span class="text-muted x-small">{{ $sub['product_sku'] }}</span>
                                    <span class="badge bg-warning bg-opacity-25 text-warning ms-1">×{{ $sub['qty'] }}</span>
                                </div>
                                <button type="button" wire:click="removeSubstitution({{ $idx }})" class="btn btn-sm btn-outline-danger py-0 px-1">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header border-bottom border-secondary border-opacity-25">
                <h5 class="mb-0 fw-bold"><i class="bi bi-truck me-2 text-success"></i>Nouvelle Livraison</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">
                            <i class="bi bi-person-badge me-1"></i>Livreur <span class="text-danger">*</span>
                        </label>
                        <select wire:model="livreur_id" class="form-select @error('livreur_id') is-invalid @enderror">
                            <option value="">-- Sélectionner un livreur --</option>
                            @foreach($livreurs as $l)
                                <option value="{{ $l->id }}">
                                    {{ $l->name }} - {{ $l->truck->plate_number ?? 'Camion' }} ({{ $l->truck->capacity ?? 0 }}kg)
                                </option>
                            @endforeach
                        </select>
                        @error('livreur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($livreur_id)
                            @php
                                $selectedLivreur = $livreurs->firstWhere('id', $livreur_id);
                            @endphp
                            @if($selectedLivreur)
                                <div class="mt-2 small text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Camion: {{ $selectedLivreur->truck->plate_number ?? 'N/A' }}
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">
                            <i class="bi bi-calendar-date me-1"></i>Date de Livraison <span class="text-danger">*</span>
                        </label>
                        <input type="date" wire:model="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror">
                        @error('delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <h6 class="fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                    <i class="bi bi-box-seam me-2 text-warning"></i>Articles de la Commande
                </h6>
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead class="table-secondary bg-opacity-10">
                            <tr>
                                <th>Produit</th>
                                <th class="text-center" style="width: 100px;">Commandé</th>
                                <th class="text-center" style="width: 100px;">Déjà Livré</th>
                                <th class="text-center" style="width: 100px;">Reste</th>
                                <th class="text-center" style="width: 120px;">Stock Dépôt</th>
                                <th class="text-center" style="width: 120px;">À Livrer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                @php
                                    $delivered = $alreadyDelivered[$item->product_id] ?? 0;
                                    $remaining = $item->quantity - $delivered;
                                    $currentQty = (int)($items[$item->product_id] ?? 0);
                                @endphp
                                <tr class="{{ $remaining <= 0 ? 'opacity-50' : '' }}">
                                    <td>
                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                        <small class="text-muted">{{ $item->product->sku ?? '—' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($delivered > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                {{ $delivered }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                            {{ $remaining }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $stock = $depotStocks[$item->product_id] ?? 0;
                                            $stockClass = $stock <= 0 ? 'danger' : ($stock < $remaining ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $stockClass }} bg-opacity-10 text-{{ $stockClass }} border border-{{ $stockClass }} border-opacity-25">
                                            <i class="bi bi-box-fill me-1"></i>{{ $stock }}
                                        </span>
                                        @if($stock < $remaining && $stock > 0)
                                            <div class="x-small text-warning mt-1" style="font-size: 0.65rem;">Stock partiel</div>
                                        @elseif($stock <= 0)
                                            <div class="x-small text-danger mt-1" style="font-size: 0.65rem;">Indisponible</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($remaining > 0 && $stock > 0)
                                            <input type="number"
                                                   wire:model.debounce.300ms="items.{{ $item->product_id }}"
                                                   class="form-control form-control-sm text-center {{ $currentQty > 0 ? 'border-primary' : '' }}"
                                                   min="0"
                                                   max="{{ min($remaining, $stock) }}">
                                        @elseif($stock <= 0 && $remaining > 0)
                                            <span class="text-danger small"><i class="bi bi-x-circle me-1"></i>En rupture</span>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check2"></i> Complet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                @if(count($substitutions) > 0)
                    <div class="alert mt-3 mb-0 small border" style="background: rgba(255, 193, 7, 0.08); border-color: rgba(255, 193, 7, 0.25) !important; color: #ffc107;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette livraison contient <strong>{{ count($substitutions) }} substitution(s)</strong>. 
                        Elle sera envoyée comme <strong>proposition</strong> et nécessitera l'approbation du client via le livreur.
                    </div>
                @endif

                @if(collect($items)->filter(fn($q) => $q > 0)->count() === 0 && count($substitutions) === 0)
                    <div class="alert alert-warning bg-opacity-10 border-opacity-25 mt-3 mb-0 small">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Veuillez entrer les quantités à livrer pour au moins un produit ou ajouter des substitutions.
                    </div>
                @endif
            </div>
            <div class="card-footer border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <a href="{{ route('depositaire.orders.show', $order) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
                <button type="button" wire:click="store" class="btn {{ count($substitutions) > 0 ? 'btn-warning' : 'btn-primary' }} px-4" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        @if(count($substitutions) > 0)
                            <i class="bi bi-send me-2"></i>Envoyer la Proposition
                        @else
                            <i class="bi bi-check2-circle me-2"></i>Créer la Livraison
                        @endif
                    </span>
                    <span wire:loading><i class="spinner-border spinner-border-sm me-2"></i>Création...</span>
                </button>
            </div>
        </div>
    </div>
</div>
