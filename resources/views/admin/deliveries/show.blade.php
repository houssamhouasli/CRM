@extends('layouts.app')
@section('title', 'Détails de la Livraison #' . $delivery->id)
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Livraison #' . $delivery->id)
@section('page-subtitle', 'Document financier de livraison')

@section('content')
<div class="row g-3">
    <div class="col-lg-12 mb-2 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.orders.show', $delivery->order_id) }}" class="btn btn-outline-custom btn-sm">
            <i class="bi bi-arrow-left"></i> Retour à la commande
        </a>
        <a href="{{ route('deliveries.print', $delivery) }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger BL (PDF)
        </a>
    </div>


    <div class="col-lg-4">
        <div class="data-card animate-in mb-3">
            <div class="card-header">
                <h5><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
            </div>
            <div class="card-body">
                <table class="table table-dark-custom table-borderless mb-0">
                    <tr><td class="text-muted">ID Livraison</td><td><strong>#{{ $delivery->id }}</strong></td></tr>
                    <tr><td class="text-muted">Commande</td><td><a href="{{ route('admin.orders.show', $delivery->order_id) }}">#{{ $delivery->order_id }}</a></td></tr>
                    <tr><td class="text-muted">Client</td><td>{{ $delivery->order->client->company_name }}</td></tr>
                    <tr><td class="text-muted">Livreur</td><td>{{ $delivery->livreur->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Dépôt</td><td>{{ $delivery->depot->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Date prévue</td><td>{{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') : '—' }}</td></tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            <span class="badge bg-{{ $delivery->status === 'livrer' ? 'success' : ($delivery->status === 'annuler' ? 'danger' : 'warning') }}">
                                {{ ['pending'=>'En attente','livrer'=>'Livrée','annuler'=>'Annulée'][$delivery->status] ?? $delivery->status }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Financial Totals Card --}}
        @if(($delivery->total_ttc ?? 0) > 0)
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-receipt me-2"></i>Récapitulatif Financier</h5></div>
            <div class="card-body">
                <table class="table table-dark-custom table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Total HT</td>
                        <td class="text-end fw-bold">{{ number_format($delivery->total_ht, 2, ',', ' ') }} MAD</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total TVA</td>
                        <td class="text-end">{{ number_format($delivery->total_tva, 2, ',', ' ') }} MAD</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--primary);">
                        <td class="fw-bold" style="color:var(--primary);">Total TTC</td>
                        <td class="text-end fw-bold" style="color:var(--primary);">{{ number_format($delivery->total_ttc, 2, ',', ' ') }} MAD</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>


    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-box-seam me-2"></i>Articles livrés</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-center">Cmd / Livré</th>
                                <th class="text-end">P.U. HT</th>
                                <th class="text-center">Promo</th>
                                <th class="text-center">TVA</th>
                                <th class="text-end">Total HT</th>
                                <th class="text-end">Total TTC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">{{ $item->product->sku ?? '—' }}</small>
                                </td>
                                <td class="text-center">
                                    {{ $item->qty_ordered }} /
                                    <span class="fw-bold {{ $item->qty_delivered >= $item->qty_ordered ? 'text-success' : 'text-warning' }}">
                                        {{ $item->qty_delivered }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($item->promo_type && $item->promo_value > 0)
                                        <div class="d-flex flex-column">
                                            <span class="text-decoration-line-through">{{ number_format($item->unit_price_ht, 2, ',', ' ') }} MAD</span>
                                            @if($item->promo_type === 'percentage')
                                                @php
                                                    $finalPrice = $item->unit_price_ht * (1 - $item->promo_value / 100);
                                                    $discount = $item->unit_price_ht - $finalPrice;
                                                @endphp
                                                <strong class="text-success">{{ number_format($finalPrice, 2, ',', ' ') }} MAD</strong>
                                                <span class="text-warning small">-{{ number_format($discount, 2, ',', ' ') }} MAD</span>
                                            @else
                                                @php
                                                    $finalPrice = $item->unit_price_ht - $item->promo_value;
                                                    $discount = $item->promo_value;
                                                @endphp
                                                <strong class="text-success">{{ number_format($finalPrice, 2, ',', ' ') }} MAD</strong>
                                                <span class="text-warning small">-{{ number_format($discount, 2, ',', ' ') }} MAD</span>
                                            @endif
                                        </div>
                                    @else
                                        <strong>{{ number_format($item->unit_price_ht ?? 0, 2, ',', ' ') }} MAD</strong>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->promo_type)
                                        @if($item->promo_type === 'percentage')
                                            @if(($item->promo_value ?? 0) > 0)
                                                <span class="badge bg-warning text-dark">-{{ number_format($item->promo_value, 1) }}%</span>
                                            @else <span class="text-muted">—</span>
                                            @endif
                                        @else
                                            @if(($item->promo_value ?? 0) > 0)
                                                <span class="badge bg-warning text-dark">-{{ number_format($item->promo_value, 2, ',', ' ') }} MAD</span>
                                            @else <span class="text-muted">—</span>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-25" style="color:#3498db;">{{ number_format($item->tva_rate ?? 20, 0) }}%</span>
                                </td>
                                <td class="text-end">{{ number_format($item->total_ht ?? 0, 2, ',', ' ') }} MAD</td>
                                <td class="text-end fw-bold">{{ number_format($item->total_ttc ?? 0, 2, ',', ' ') }} MAD</td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if(($delivery->total_ttc ?? 0) > 0)
                        <tfoot>
                            <tr style="border-top: 2px solid rgba(10,59,143,0.3);">
                                <td colspan="7" class="text-end">
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <div class="mt-2" style="border-top: 1px solid rgba(10,59,143,0.2); padding-top: 8px;">
                                            <span class="text-muted small me-3">Sous-total HT:</span> <strong>{{ number_format($delivery->total_ht, 2, ',', ' ') }} MAD</strong>
                                        </div>
                                        <div><span class="text-muted small me-3">TVA:</span> {{ number_format($delivery->total_tva, 2, ',', ' ') }} MAD</div>
                                        <div style="color:var(--primary); font-size: 1.1rem;">
                                            <span class="me-3">TOTAL TTC:</span> <strong>{{ number_format($delivery->total_ttc, 2, ',', ' ') }} MAD</strong>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
