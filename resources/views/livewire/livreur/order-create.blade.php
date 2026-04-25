<div class="row g-4">
    @if(session()->has('success'))
        <div class="col-12">
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="col-12">
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="col-12">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="col-lg-8 animate-in">
        <div class="data-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Catalogue Produits</h5>
                <div class="d-flex w-50" style="max-width:300px;">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm"
                        placeholder="Rechercher...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y:auto;">
                    <table class="table table-dark-custom mb-0">
                        <thead class="sticky-top">
                            <tr>
                                <th>Produit</th>
                                <th>Prix HT</th>
                                <th>TVA</th>
                                <th>Promo</th>
                                <th>Prix TTC</th>
                                <th style="min-width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                                                <i class="bi bi-box-seam fs-5"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-secondary">{{ $product->sku }}</small>
                                                @if($product->category)
                                                    <br><span class="badge bg-info bg-opacity-10 text-info small">{{ $product->category->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($product->price_ht, 2, ',', ' ') }} MAD</td>
                                    <td><span class="badge bg-secondary">{{ $product->tva_rate }}%</span></td>
                                    <td>
                                        @if($product->isPromoActive())
                                            @if($product->promo_type === 'percentage')
                                                <span
                                                    class="badge bg-danger mb-1">-{{ number_format($product->promo_value, 0) }}%</span>
                                            @else
                                                <span
                                                    class="badge bg-danger mb-1">-{{ number_format($product->promo_value, 2, ',', ' ') }}
                                                    MAD</span>
                                            @endif
                                            <br><small class="text-secondary" style="font-size: 0.75rem;">(dès
                                                {{ $product->promo_min_qty }}
                                                {{ Str::plural('unité', $product->promo_min_qty) }})</small>
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $ttc = $product->price_ht * (1 + ($product->tva_rate / 100));
                                        @endphp
                                        <span class="fw-bold"
                                            style="color:var(--primary);">{{ number_format($ttc, 2, ',', ' ') }} MAD</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if(isset($cart[$product->id]))
                                                <button wire:click="decrementQty({{ $product->id }})" class="btn btn-sm btn-outline-secondary" style="width: 28px; height: 28px; padding: 0;">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" wire:change="updateQuantity({{ $product->id }}, $event.target.value)" value="{{ $cart[$product->id] }}" min="1" class="form-control form-control-sm text-center" style="width: 50px; padding: 0.25rem;">
                                                <button wire:click="incrementQty({{ $product->id }})" class="btn btn-sm btn-outline-primary" style="width: 28px; height: 28px; padding: 0;">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                                <button wire:click="removeFromCart({{ $product->id }})" class="btn btn-sm btn-link text-danger p-0 ms-1">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button wire:click="addToCart({{ $product->id }})" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-cart-plus"></i> Ajouter
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted mb-3 d-block"></i>
                                        <span class="text-muted">Aucun produit trouvé.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4 animate-in" style="animation-delay: 0.1s;">
        <div class="data-card h-100 d-flex flex-column">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="bi bi-cart4 me-2"></i>Nouvelle Commande</h5>
            </div>

            <div class="card-body flex-grow-1 overflow-auto">
                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase fw-bold">1. Sélectionner le Client</label>
                    <select wire:model="client_id" class="form-select border-color bg-transparent shadow-none focus-ring focus-ring-primary">
                        <option value="" class="text-dark">-- Choisissez un client --</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" class="text-dark">{{ $c->company_name }}</option>
                        @endforeach
                    </select>
                </div>

                @error('client_id') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror

                <label class="form-label text-secondary small text-uppercase fw-bold mt-4">2. Sélection Produits</label>
                @if(count($cartItems) > 0)
                    <div class="list-group list-group-flush bg-transparent">
                        @foreach($cartItems as $item)
                            <div class="list-group-item bg-transparent border-color p-3 mb-2 rounded" style="border: 1px solid var(--border-color);">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $item['product']->name }}</div>
                                            <small class="text-secondary">{{ $item['product']->sku }}</small>
                                        </div>
                                    </div>
                                    <button wire:click="removeFromCart({{ $item['product']->id }})" class="btn btn-link text-danger p-0">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>


                                <div class="mb-3 bg-dark bg-opacity-25 rounded p-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-secondary">Prix unitaire HT:</span>
                                        <span>{{ number_format($item['unitPriceHt'], 2) }} MAD</span>
                                    </div>
                                    @if($item['promoApplied'])
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-secondary">Remise unitaire:</span>
                                            <span class="text-success">-{{ number_format($item['discountPerUnit'], 2) }} MAD</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-secondary">Prix HT après remise:</span>
                                            <span>{{ number_format($item['unitPriceHt'] - $item['discountPerUnit'], 2) }} MAD</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-secondary">TVA ({{ $item['product']->tva_rate }}%):</span>
                                        <span>{{ number_format($item['unitPriceTva'], 2) }} MAD</span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-1 border-top border-secondary border-opacity-25 pt-1 mt-1">
                                        <span class="text-secondary">Prix unitaire TTC:</span>
                                        <span class="fw-bold">
                                            @if($item['promoApplied'])
                                                <span class="text-decoration-line-through text-secondary small">{{ number_format($item['originalPriceTtc'], 2) }}</span>
                                                <span class="text-success">{{ number_format($item['unitPriceTtc'], 2) }} MAD</span>
                                            @else
                                                {{ number_format($item['unitPriceTtc'], 2) }} MAD
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between small mt-2 pt-1 border-top border-primary border-opacity-50">
                                        <span class="text-secondary">Total ligne HT:</span>
                                        <span>{{ number_format($item['lineTotalHt'], 2) }} MAD</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-secondary">TVA ligne:</span>
                                        <span>{{ number_format($item['lineTotalTva'], 2) }} MAD</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold" style="color: var(--primary);">
                                        <span>Total ligne TTC:</span>
                                        <span>{{ number_format($item['lineTotalTtc'], 2) }} MAD</span>
                                    </div>
                                    @if($item['promoApplied'])
                                        <div class="d-flex justify-content-between small text-success">
                                            <span><i class="bi bi-check-circle me-1"></i>Économie:</span>
                                            <span>-{{ number_format($item['totalDiscount'] * (1 + $item['product']->tva_rate/100), 2) }} MAD</span>
                                        </div>
                                    @endif
                                </div>


                                @if($item['hasPromo'])
                                    <div class="mb-3">
                                        @if($item['promoApplied'])
                                            <span class="badge bg-success">
                                                <i class="bi bi-tag-fill me-1"></i>
                                                @if($item['product']->promo_type === 'percentage')
                                                    -{{ number_format($item['product']->promo_value, 0) }}% appliqué
                                                @else
                                                    -{{ number_format($item['product']->promo_value, 2) }} MAD appliqué
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Promo à partir de {{ $item['product']->promo_min_qty }} unités
                                            </span>
                                        @endif
                                    </div>
                                @endif


                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-secondary small">Quantité:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <button wire:click="decrementQty({{ $item['product']->id }})" class="btn btn-sm btn-outline-secondary" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" wire:change="updateQuantity({{ $item['product']->id }}, $event.target.value)" value="{{ $item['qty'] }}" min="1" class="form-control form-control-sm text-center fw-bold" style="width: 60px;">
                                        <button wire:click="incrementQty({{ $item['product']->id }})" class="btn btn-sm btn-outline-primary" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 rounded" style="background:var(--bg-secondary);">
                        <i class="bi bi-cart-x fs-2 text-secondary mb-2"></i>
                        <p class="mb-0 text-muted small">Panier vide. Ajoutez des produits.</p>
                    </div>
                @endif
                @error('cart') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
            </div>


            <div class="card-footer bg-transparent border-top border-color">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Total HT</span>
                    <span>{{ number_format($total_ht, 2, ',', ' ') }} MAD</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">TVA</span>
                    <span>{{ number_format($total_tva, 2, ',', ' ') }} MAD</span>
                </div>
                <div class="d-flex justify-content-between align-items-end mb-3 mt-3 pt-3 border-top border-color">
                    <span class="fs-5 fw-bold text-white">Total TTC</span>
                    <span class="fs-4 fw-bold"
                        style="color:var(--primary);">{{ number_format($total_ttc, 2, ',', ' ') }} MAD</span>
                </div>

                <button type="button" wire:click="submitOrder" wire:loading.attr="disabled"
                    {{ count($cart) == 0 || !$client_id ? 'disabled' : '' }}
                    class="btn w-100 {{ count($cart) == 0 || !$client_id ? 'btn-secondary opacity-50' : 'btn-primary' }}"
                    style="padding: 12px;">
                    <span wire:loading.remove wire:target="submitOrder">
                        <i class="bi bi-check-lg me-2"></i>Créer la Commande
                    </span>
                    <span wire:loading wire:target="submitOrder">
                        <span class="spinner-border spinner-border-sm me-2"></span>Création...
                    </span>
                </button>
                @if(count($cart) == 0 || !$client_id)
                    <div class="text-warning small mt-2">
                        @if(count($cart) == 0)
                            <i class="bi bi-exclamation-triangle me-1"></i>Ajoutez des produits au panier
                        @elseif(!$client_id)
                            <i class="bi bi-exclamation-triangle me-1"></i>Sélectionnez un client
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>