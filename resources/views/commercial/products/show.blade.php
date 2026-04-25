@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', $product->name)
@section('page-subtitle', 'Détails du produit')

@section('content')
<div class="row g-3">

    <div class="col-lg-6">
        <div class="data-card animate-in h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-box-seam me-2"></i>Informations Générales</h5>
                <span class="badge bg-secondary">{{ $product->category->name }}</span>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center py-4 bg-dark bg-opacity-10 rounded-3">
                    <h3 class="fw-bold text-dark mb-2">{{ $product->name }}</h3>
                    <div class="fs-2 fw-bold" style="color: var(--primary-light);">{{ number_format($product->price_ht, 2, ',', ' ') }} DH <small class="fs-4 text-muted font-normal">(HT)</small></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-custom table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 140px;">SKU</td>
                            <td><strong class="text-primary">{{ $product->sku ?? '—' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catégorie</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Unité</td>
                            <td>{{ $product->unit ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">TVA (%)</td>
                            <td><span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10">{{ number_format($product->tva_rate, 0, ',', ' ') }}%</span></td>
                        </tr>
                    </table>
                </div>

                @if($product->promo_value > 0 && (!$product->promo_end_date || $product->promo_end_date >= now()))
                @php
                    $discountPerUnit = $product->calculateDiscountPerUnit($product->promo_min_qty ?: 1);
                    $finalPriceHt = $product->price_ht - $discountPerUnit;
                @endphp
                <div class="mt-4 pt-4 border-top border-secondary border-opacity-25 animate-in" style="animation-delay:0.1s;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-megaphone text-warning me-2"></i>
                        <h6 class="mb-0 text-warning">Offre Promotionnelle</h6>
                    </div>
                    <div class="p-3 rounded-3" style="background: rgba(243, 156, 18, 0.08); border: 1px dashed rgba(243, 156, 18, 0.3);">
                        <div class="row align-items-center">
                            <div class="col-auto text-center border-end border-secondary border-opacity-25 pe-3">
                                <div class="bg-warning text-dark fw-bold rounded-pill px-3 py-1 shadow-sm mb-2" style="font-size: 0.85rem;">
                                    @if($product->promo_type === 'percentage')
                                        -{{ number_format($product->promo_value, 0) }}%
                                    @else
                                        -{{ number_format($product->promo_value, 2, ',', ' ') }} DH
                                    @endif
                                </div>
                                <div class="fw-bold fs-5">{{ number_format($finalPriceHt, 2, ',', ' ') }} <small style="font-size: 0.65rem;">DH/HT</small></div>
                                <div class="text-muted text-decoration-line-through x-small">{{ number_format($product->price_ht, 2, ',', ' ') }} DH</div>
                            </div>
                            <div class="col ps-3">
                                <div class="small fw-bold text-warning mb-1">
                                    @if($product->promo_min_qty > 1)
                                        Applicable dès {{ $product->promo_min_qty }} {{ $product->unit }} achetés
                                    @else
                                        Offre immédiate
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    @if($product->promo_start_date && $product->promo_end_date)
                                        Du {{ $product->promo_start_date->format('d/m/Y') }} au {{ $product->promo_end_date->format('d/m/Y') }}
                                    @elseif($product->promo_end_date)
                                        Jusqu'au {{ $product->promo_end_date->format('d/m/Y') }}
                                    @elseif($product->promo_start_date)
                                        À partir du {{ $product->promo_start_date->format('d/m/Y') }}
                                    @else
                                        Validité permanente
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <h6 class="mb-0">Note</h6>
                    </div>
                    <p class="text-muted small italic">Les détails du produit sont en lecture seule pour votre profil commercial.</p>
                </div>

                <div class="mt-4">
                    <a href="{{ route('commercial.products.index') }}" class="btn btn-outline-custom w-100">
                        <i class="bi bi-arrow-left me-2"></i>Retour au catalogue
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-activity me-2"></i>Derniers Mouvements</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Dépôt</th>
                                <th>Par</th>
                                <th>Type</th>
                                <th class="text-center">Qté</th>
                                <th>Raison</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $move)
                            <tr>
                                <td><small class="text-muted">#{{ $loop->iteration }}</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock-history me-2 text-muted small"></i>
                                        <small>{{ $move->moved_at ? $move->moved_at->format('d/m/Y H:i') : '—' }}</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary opacity-75">{{ $move->depot->name ?? '—' }}</span></td>
                                <td><small class="text-muted">{{ $move->user->name ?? '—' }}</small></td>
                                <td>
                                    <span class="badge {{ $move->type === 'in' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $move->type === 'in' ? 'success' : 'danger' }} py-1 px-2 border {{ $move->type === 'in' ? 'border-success' : 'border-danger' }} border-opacity-25" style="font-size: 0.7rem;">
                                        {{ $move->type === 'in' ? 'ENTRÉE' : 'SORTIE' }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold {{ $move->type === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $move->type === 'in' ? '+' : '-' }}{{ $move->quantity }}
                                </td>
                                <td><small class="text-muted">{{ $move->reason ?? '—' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Aucun mouvement récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
