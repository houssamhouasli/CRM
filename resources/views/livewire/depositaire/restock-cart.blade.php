<div>
    @if(session()->has('error')) 
        <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger animate-in"
            role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="mb-4 animate-in" style="max-width: 500px;">
                <div class="search-wrapper shadow-sm rounded-3 overflow-hidden border border-secondary border-opacity-25 transition-all">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-opacity-25 py-2 px-3">
                            <i class="bi bi-search text-secondary small"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                            class="form-control border-0 bg-opacity-25 py-2 ps-0 small" 
                            placeholder="Rechercher un produit (Nom, Réf)..."
                            style="font-size: 0.9rem;">
                    </div>
                </div>
            </div>

            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div class="category-group mb-5 animate-in">
                        <div class="d-flex align-items-center mb-3 px-2">
                            <div class="category-icon me-3">
                                <i class="bi bi-collection-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-800" style="color: var(--secondary);">{{ $category->name }}</h4>
                                <p class="text-secondary small mb-0">{{ $category->products->count() }} produits disponibles</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            @foreach($category->products as $product)
                                <div class="col-md-6 col-xl-4">
                                    <div class="product-card {{ isset($selectedProducts[$product->id]) ? 'selected' : '' }}"
                                        wire:click="toggleProduct({{ $product->id }})">

                                        @if(isset($selectedProducts[$product->id]))
                                            <div class="selection-badge">
                                                <i class="bi bi-check-lg"></i>
                                            </div>
                                        @endif

                                        <div class="product-card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="product-avatar"
                                                    style="background: linear-gradient(135deg, rgba(200,16,46,0.2), rgba(26,26,46,1));">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                @if($product->promo_type)
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-tag-fill me-1"></i>{{ $product->promo_type === 'percentage' ? '-' . $product->promo_value . '%' : '-' . $product->promo_value . ' DH' }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h6 class="product-title fw-700 mb-0 text-truncate text-primary">{{ $product->name }}</h6>
                                            <div class="text-secondary x-small mb-2">REF: {{ $product->sku ?? '—' }}</div>
                                            <p class="product-desc text-secondary small mb-3 text-truncate-2">
                                                {{ $product->description ?? 'Qualité Lesaffre garantie.' }}</p>

                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <div class="stock-status">
                                                    <span class="dot"></span>
                                                    En Catalogue
                                                </div>

                                                @if(isset($selectedProducts[$product->id]))
                                                    <div class="qty-control stop-propagation" onclick="event.stopPropagation()">
                                                        <button type="button"
                                                            wire:click="updateQuantity({{ $product->id }}, {{ $selectedProducts[$product->id] - 1 }})"><i
                                                                class="bi bi-dash"></i></button>
                                                        <input type="number" value="{{ $selectedProducts[$product->id] }}"
                                                            wire:change="updateQuantity({{ $product->id }}, $event.target.value)"
                                                            min="1">
                                                        <button type="button"
                                                            wire:click="updateQuantity({{ $product->id }}, {{ $selectedProducts[$product->id] + 1 }})"><i
                                                                class="bi bi-plus"></i></button>
                                                    </div>
                                                @else
                                                    <div class="add-btn">
                                                        <i class="bi bi-plus-circle-fill"></i> Ajouter
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="order-summary-card sticky-top" style="top: 100px;">
                <div class="summary-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="summary-icon">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                        <h5 class="mb-0 fw-800 text-white">Votre Panier</h5>
                    </div>
                    <span
                        class="badge bg-primary-subtle text-primary rounded-pill">{{ count($selectedProducts) }}</span>
                </div>

                <div class="summary-body">
                    @if($cartItems && $cartItems->count() > 0)
                        <div class="cart-items-list mb-4 custom-scrollbar" style="max-height: 350px; overflow-y: auto;">
                            @foreach($cartItems as $item)
                                <div class="cart-item animate-in">
                                    <div class="cart-item-info">
                                        <div class="fw-700 text-white small">{{ $item->name }}</div>
                                        <div class="text-secondary x-small">Qté: {{ $selectedProducts[$item->id] }} {{ $item->unit }}</div>
                                    </div>
                                    <button type="button" class="btn-remove" wire:click="toggleProduct({{ $item->id }})">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info bg-opacity-10 border-opacity-25 mb-4 small">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ array_sum($selectedProducts) }} produit(s) dans votre demande
                        </div>
                    @else
                        <div class="empty-cart text-center text-white py-5 opacity-50">
                            <div class="empty-icon mb-3">
                                <i class="bi bi-cart-x"></i>
                            </div>
                            <p class="small fw-600 mb-0">Votre panier est encore vide</p>
                            <p class="x-small">Sélectionnez des produits à gauche</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label-custom">Notes / Instructions</label>
                        <textarea wire:model="notes" class="form-control-custom" rows="3"
                            placeholder="Instructions particulières..."></textarea>
                    </div>

                    <button type="button" wire:click="submitRequest"
                        class="btn-submit-order w-100 {{ empty($selectedProducts) ? 'disabled' : '' }}">
                        <span>ENVOYER LA DEMANDE</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div wire:loading wire:target="submitRequest" class="mt-3 text-center">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="ms-2 small text-primary fw-700">Traitement en cours...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-dark-glass { background: rgba(10, 20, 40, 0.4) !important; backdrop-filter: blur(10px); }
        .search-wrapper:focus-within {
            border-color: var(--primary) !important;
            box-shadow: 0 0 20px rgba(0, 48, 135, 0.2) !important;
            transform: scale(1.01);
        }
        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }
        .x-small { font-size: 0.7rem; }

        /* Category Icon */
        .category-icon {
            width: 44px; height: 44px;
            background: rgba(0, 48, 135, 0.1);
            color: var(--primary);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(0, 48, 135, 0.1);
        }

        /* Product Card */
        .product-card {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
            overflow: hidden;
            user-select: none;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            background: var(--bg-card-hover);
        }

        .product-card.selected {
            border-color: var(--primary);
            background: rgba(0, 48, 135, 0.04);
            box-shadow: 0 0 0 1px var(--primary);
        }

        .product-card-body {
            padding: 1.5rem;
            display: flex; flex-direction: column; height: 100%;
        }

        .selection-badge {
            position: absolute; top: 0; right: 0;
            background: var(--primary);
            color: #fff; padding: 4px 8px; border-radius: 0 0 0 15px;
            font-size: 0.9rem; z-index: 2;
        }

        .product-avatar {
            width: 54px; height: 54px; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: var(--primary-light);
            border: 1px solid var(--glass-border);
        }

        .product-price {
            font-size: 1.3rem; font-weight: 800;
            color: var(--secondary); line-height: 1;
        }

        .product-unit { font-size: 0.75rem; }

        .product-desc {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; line-height: 1.4;
        }

        .stock-status {
            font-size: 0.7rem; font-weight: 700;
            color: var(--success); display: flex; align-items: center; gap: 6px;
            background: rgba(39, 174, 96, 0.1); padding: 4px 10px; border-radius: 50px;
        }

        .stock-status .dot {
            width: 6px; height: 6px; background: currentColor; border-radius: 50%;
            box-shadow: 0 0 8px currentColor;
        }

        /* Qty Control */
        .qty-control {
            display: flex; align-items: center; background: #f0f2f5;
            border-radius: 50px; padding: 3px; border: 1px solid var(--glass-border);
        }

        .qty-control button {
            width: 28px; height: 28px; border-radius: 50%; border: none;
            background: #ffffff; color: var(--secondary);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }

        .qty-control button:hover { background: var(--primary); }

        .qty-control input {
            width: 40px; background: transparent; border: none;
            color: var(--secondary); text-align: center;
            font-weight: 800; font-size: 0.9rem; outline: none;
        }

        .add-btn {
            color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;
            transition: color 0.2s;
        }

        .product-card:hover .add-btn { color: var(--primary-light); }

        /* Order Summary */
        .order-summary-card {
            background: var(--bg-card); border: 1px solid var(--glass-border);
            border-radius: 24px; overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            background: linear-gradient(165deg, #0e1e3a 0%, #091428 100%);
        }

        .summary-header {
            padding: 1.5rem; border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .summary-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem;
        }

        .summary-body { padding: 1.5rem; }

        .cart-item {
            display: grid; grid-template-columns: 1fr auto auto;
            align-items: center; gap: 12px; padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            position: relative;
        }

        .cart-item:last-child { border-bottom: none; }

        .btn-remove {
            background: transparent; border: none;
            color: var(--text-secondary); font-size: 1.2rem;
            opacity: 0.3; transition: all 0.2s; padding: 0;
        }

        .cart-item:hover .btn-remove { opacity: 1; color: var(--danger); }

        .total-section { background: rgba(255, 255, 255, 0.03); }

        .total-amount {
            color: var(--primary-light); font-size: 1.4rem; font-weight: 900;
            text-shadow: 0 0 20px rgba(0, 48, 135, 0.4);
        }

        .form-label-custom {
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1px; color: var(--text-secondary);
            margin-bottom: 8px; display: block;
        }

        .form-control-custom {
            width: 100%; background: #f8f9fb; border: 1px solid var(--border-color);
            border-radius: 12px; color: var(--text-primary);
            padding: 12px; font-size: 0.85rem; transition: all 0.2s;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 48, 135, 0.1);
            outline: none;
        }

        .btn-submit-order {
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none; border-radius: 16px; padding: 14px;
            color: #fff; font-weight: 900; font-size: 0.9rem;
            letter-spacing: 1px; display: flex; align-items: center;
            justify-content: center; gap: 12px; transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0, 48, 135, 0.2);
        }

        .btn-submit-order:not(.disabled):hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 48, 135, 0.35);
        }

        .btn-submit-order.disabled { filter: grayscale(1); opacity: 0.5; cursor: not-allowed; }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--glass-border); border-radius: 10px;
        }

        @media (max-width: 991.98px) {
            .order-summary-card { margin-top: 2rem; }
        }
    </style>
</div>
