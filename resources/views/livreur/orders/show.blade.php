@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar-livreur')
@endsection

@section('page-title', 'Détails de la Commande #' . $order->id)
@section('page-subtitle', 'Consultez les produits et les totaux de cette commande')

@section('content')

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <a href="{{ route('livreur.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour à mes commandes
            </a>
            <a href="{{ route('orders.print', $order) }}" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="bi bi-file-pdf me-1"></i>Télécharger PDF
            </a>
        </div>

        <span class="badge fs-6 py-2 px-3 bg-{{ 
            $order->status === 'delivered' ? 'success' :
        ($order->status === 'cancelled' ? 'danger' :
            ($order->status === 'confirmed' ? 'info' : 'warning')) 
        }} bg-opacity-10 text-{{ 
            $order->status === 'delivered' ? 'success' :
        ($order->status === 'cancelled' ? 'danger' :
            ($order->status === 'confirmed' ? 'info' : 'warning')) 
        }} border border-{{ 
            $order->status === 'delivered' ? 'success' :
        ($order->status === 'cancelled' ? 'danger' :
            ($order->status === 'confirmed' ? 'info' : 'warning')) 
        }} border-opacity-25 shadow-sm" style="border-radius: var(--radius-md);">
            État actuel : {{ strtoupper($order->status) }}
        </span>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 animate-in">
            <div class="data-card h-100">
                <div class="card-header border-bottom border-color">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-building text-primary me-2"></i>Informations Client</h6>
                </div>
                <div class="card-body">
                    @if($order->client)
                        <div class="mb-3">
                            <small class="text-secondary text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Raison
                                Sociale</small>
                            <div class="fw-bold fs-5 text-primary">{{ $order->client->company_name }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-secondary text-uppercase fw-semibold"
                                style="letter-spacing: 0.5px;">Adresse</small>
                            <div class="text-dark d-flex align-items-start mt-1">
                                <i class="bi bi-geo-alt text-muted mt-1 me-2"></i>
                                <span>{{ $order->client->address }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <small class="text-secondary text-uppercase fw-semibold"
                                style="letter-spacing: 0.5px;">Contact</small>
                            <div class="text-dark mt-1">
                                <div class="mb-1"><i
                                        class="bi bi-telephone text-muted me-2"></i>{{ $order->client->phone ?? 'Non spécifié' }}
                                </div>
                                <div><i class="bi bi-envelope text-muted me-2"></i>{{ $order->client->email ?? 'Non spécifié' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-muted d-flex align-items-center h-100 justify-content-center flex-column py-4">
                            <i class="bi bi-exclamation-circle fs-3 mb-2 opacity-50"></i>
                            <p class="mb-0 text-center">Les informations du client<br>ne sont plus disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8 animate-in" style="animation-delay: 0.1s;">
            <div class="data-card h-100">
                <div class="card-header border-bottom border-color d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam text-primary me-2"></i>Produits Commandés</h6>
                    <span class="badge bg-secondary bg-opacity-25 text-light">{{ $order->items->count() }} articles</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead class="text-secondary">
                                <tr>
                                    <th class="ps-4">Produit</th>
                                    <th class="text-end">Qté</th>
                                    <th class="text-end">Prix U HT</th>
                                    <th class="text-center">Promo</th>
                                    <th class="text-end">Prix Final HT</th>
                                    <th class="text-end">Total HT</th>
                                    <th class="text-end">TVA</th>
                                    <th class="text-end">Total TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-primary">{{ $item->product->name ?? 'Produit Supprimé' }}</div>
                                            @if($item->product)
                                                <small class="text-muted">Réf: {{ $item->product->sku }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace">
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->price_unit_ht, 2, ',', ' ') }} MAD
                                        </td>
                                        <td class="text-center">
                                            @if($item->promo_type && $item->promo_value > 0)
                                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2 py-1">
                                                    @if($item->promo_type === 'percentage')
                                                        -{{ number_format($item->promo_value, 0) }}%
                                                    @else
                                                        -{{ number_format($item->promo_value, 2) }} MAD
                                                    @endif
                                                </span>
                                                <div class="small text-muted mt-1">-{{ number_format($item->discount_amount, 2) }} MAD</div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->final_price_ht ?? $item->price_unit_ht, 2, ',', ' ') }} MAD
                                        </td>
                                        <td class="text-end">
                                            {{ number_format(($item->final_price_ht ?? $item->price_unit_ht) * $item->quantity, 2, ',', ' ') }} MAD
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->total_tva, 2, ',', ' ') }} MAD
                                        </td>
                                        <td class="text-end fw-bold" style="color:var(--primary);">
                                            {{ number_format($item->total_ttc, 2, ',', ' ') }} MAD
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top border-color">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-muted small">
                            Poids total estimé : <strong class="text-dark">{{ $order->items->sum(function ($item) {
                                return $item->quantity * ($item->product->weight ?? 0);
                            }) }} kg</strong>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="d-flex justify-content-md-end justify-content-between mb-1">
                                <span class="text-secondary text-uppercase fw-semibold me-4"
                                    style="letter-spacing: 0.5px;">TVA</span>
                                <span>{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</span>
                            </div>
                            <div
                                class="d-flex justify-content-md-end justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-25">
                                <span class="text-dark text-uppercase fw-bold me-4">TOTAL TTC</span>
                                <span class="fs-4 fw-bold"
                                    style="color: var(--primary);">{{ number_format($order->total_ttc, 2, ',', ' ') }}
                                    MAD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection