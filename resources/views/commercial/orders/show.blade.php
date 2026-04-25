@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Commande #' . $order->id)
@section('page-subtitle', $order->client->company_name)

@section('topbar-actions')
    <a href="{{ route('orders.print', $order) }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger PDF
    </a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="data-card animate-in mb-3">
                <div class="card-header"><h5><i class="bi bi-info-circle me-2"></i>Informations</h5></div>
                <div class="card-body">
                    <table class="table table-dark-custom table-borderless mb-0">
                        <tr><td class="text-muted">Client</td><td><strong>{{ $order->client->company_name }}</strong></td></tr>
                        <tr><td class="text-muted">Région</td><td>{{ $order->client->region->name ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Date</td><td>{{ $order->order_date->format('d/m/Y H:i') }}</td></tr>
                        <tr><td class="text-muted">Montant HT</td><td>{{ number_format($order->total_ht, 2, ',', ' ') }} MAD</td></tr>
                        <tr><td class="text-muted">Total TVA</td><td>{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</td></tr>
                        <tr><td class="text-muted">TOTAL TTC</td><td><strong style="color:var(--primary-light);">{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</strong></td></tr>
                        <tr>
                            <td class="text-muted">Statut</td>
                            <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        </tr>
                        @if($order->notes)
                            <tr><td class="text-muted">Notes</td><td>{{ $order->notes }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="data-card animate-in">
                <div class="card-header"><h5><i class="bi bi-list-ul me-2"></i>Lignes de commande</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead><tr><th>Produit</th><th class="text-center">Quantité</th><th class="text-center">Qté Livrée</th><th class="text-end">Prix unitaire</th><th class="text-end">Total HT</th><th class="text-end">Total TVA</th><th class="text-end">Sous-total</th></tr></thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="fw-bold d-block text-primary">{{ $item->product->name }}</span>
                                                    <small class="text-muted">{{ $item->product->sku ?? '—' }} • TVA {{ number_format($item->tva_rate, 0) }}%</small>
                                                    @if($item->promo_type && $item->promo_value > 0)
                                                        <br><span class="badge bg-danger mt-1" style="font-size: 0.7rem;">
                                                            <i class="bi bi-tag-fill me-1"></i>
                                                            @if($item->promo_type === 'percentage')
                                                                -{{ number_format($item->promo_value, 0) }}%
                                                            @else
                                                                -{{ number_format($item->promo_value, 2) }} MAD
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                        <td class="text-center">
                                            <span class="fw-bold {{ $item->delivered >= $item->quantity ? 'text-success' : 'text-warning' }}">
                                                {{ $item->delivered }} {{ $item->product->unit }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($item->promo_type && $item->promo_value > 0)
                                                <div class="d-flex flex-column">
                                                    <span class="text-decoration-line-through text-muted small">{{ number_format($item->price_unit_ht, 2, ',', ' ') }} MAD</span>
                                                    @if($item->promo_type === 'percentage')
                                                        <strong class="text-danger">{{ number_format($item->final_price_ht ?? ($item->price_unit_ht * (1 - $item->promo_value/100)), 2, ',', ' ') }} MAD</strong>
                                                    @else
                                                        <strong class="text-danger">{{ number_format($item->final_price_ht ?? ($item->price_unit_ht - $item->promo_value), 2, ',', ' ') }} MAD</strong>
                                                    @endif
                                                </div>
                                            @else
                                                <strong>{{ number_format($item->price_unit_ht, 2, ',', ' ') }} MAD</strong>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @php
                                                $finalPrice = $item->final_price_ht ?? $item->price_unit_ht;
                                                $itemTotalHt = $finalPrice * $item->quantity;
                                            @endphp
                                            {{ number_format($itemTotalHt, 2, ',', ' ') }} MAD
                                        </td>
                                        <td class="text-end">{{ number_format($item->total_tva, 2, ',', ' ') }} MAD</td>
                                        <td class="text-end">{{ number_format($item->total_ttc, 2, ',', ' ') }} MAD</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top: 2px solid rgba(255,255,255,0.1);">
                                    <td colspan="6" class="text-end text-muted">Sous-total HT</td>
                                    <td class="text-end text-muted">{{ number_format($order->total_ht, 2, ',', ' ') }} MAD</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end text-muted">Total TVA</td>
                                    <td class="text-end text-muted">{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</td>
                                </tr>
                                <tr style="border-top: 2px solid var(--primary);">
                                    <td colspan="6" class="text-end"><strong>TOTAL TTC</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="data-card animate-in mt-3">
                <div class="card-header"><h5><i class="bi bi-truck me-2"></i>Livraisons de cette commande</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Livreur (Camion)</th>
                                    <th>Dépôt</th>
                                    <th>Statut</th>
                                    <th>Date de livraison</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->deliveries as $delivery)
                                    <tr>
                                        <td><strong>#{{ $delivery->id }}</strong></td>
                                        <td>{{ $delivery->livreur->name ?? '—' }}</td>
                                        <td>{{ $delivery->depot->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $delivery->status === 'delivered' || $delivery->status === 'completed' ? 'success' : ($delivery->status === 'cancelled' ? 'danger' : ($delivery->status === 'partial' ? 'info' : 'warning')) }}">
                                                {{ ['pending' => 'En attente', 'partial' => 'Livrée Partielle', 'completed' => 'Livrée Complète', 'cancelled' => 'Annulée'][$delivery->status] ?? $delivery->status }}
                                            </span>
                                        </td>
                                        <td>{{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') : '—' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('commercial.deliveries.show', $delivery) }}" class="btn btn-outline-custom btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune livraison n'a encore été enregistrée pour cette commande</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
