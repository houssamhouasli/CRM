@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', ($order->type === 'restock' ? 'Réapprovisionnement #' : 'Commande #') . $order->id)
@section('page-subtitle', $order->type === 'restock' ? ($order->creator->depot->name ?? 'Dépôt') : ($order->client->company_name ?? 'Client'))

@section('topbar-actions')
    @if($order->type !== 'restock')
        <a href="{{ route('orders.print', $order) }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger PDF
        </a>
    @endif
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="data-card animate-in mb-3">
                <div class="card-header"><h5><i class="bi bi-info-circle me-2"></i>Informations</h5></div>
                <div class="card-body">
                    <table class="table table-dark-custom table-borderless mb-0">
                        @if($order->type === 'restock')
                            <tr><td class="text-muted">Dépositaire</td><td><strong>{{ $order->creator->name }}</strong></td></tr>
                            <tr><td class="text-muted">Dépôt</td><td><strong>{{ $order->creator->depot->name ?? '—' }}</strong></td></tr>
                        @else
                            <tr><td class="text-muted">Client</td><td><strong>{{ $order->client->company_name }}</strong></td></tr>
                            <tr><td class="text-muted">Région</td><td>{{ $order->client->region->name ?? '—' }}</td></tr>
                        @endif
                        <tr><td class="text-muted">Date</td><td>{{ $order->order_date->format('d/m/Y H:i') }}</td></tr>

                        @if($order->type !== 'restock')
                            <tr><td class="text-muted">Montant HT</td><td>{{ number_format($order->total_ht, 2, ',', ' ') }} MAD</td></tr>
                            <tr><td class="text-muted">Total TVA</td><td>{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</td></tr>
                            <tr><td class="text-muted">TOTAL TTC</td><td><strong style="color:var(--primary-light);">{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</strong></td></tr>
                        @endif

                        <tr>
                            <td class="text-muted">Statut</td>
                            <td>
                                @php
                                    $statusLabel = $order->status_label;
                                    if ($order->type === 'restock') {
                                        $statusLabel = [
                                            'pending' => 'En attente',
                                            'confirmed' => 'Expédiée / Confirmée',
                                            'livrer' => 'Reçue par le Dépôt',
                                            'annuler' => 'Refusée',
                                        ][$order->status] ?? $order->status;
                                    }
                                @endphp
                                <span class="badge-status badge-{{ $order->status }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="data-card animate-in">
                <div class="card-header"><h5><i class="bi bi-arrow-repeat me-2"></i>Changer le statut</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                                    {{ $order->type === 'restock' ? 'Approuver & Expédier' : 'Confirmée' }}
                                </option>
                                <option value="livrer" {{ $order->status === 'livrer' ? 'selected' : '' }}>
                                    {{ $order->type === 'restock' ? 'Livrée au dépôt' : 'Livré' }}
                                </option>
                                <option value="annuler" {{ $order->status === 'annuler' ? 'selected' : '' }}>Annulée / Refusée</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom btn-sm">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="data-card animate-in">
                <div class="card-header"><h5><i class="bi bi-list-ul me-2"></i>Produits demandés</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    @if($order->type !== 'restock')
                                        <th class="text-center">Qté Livrée</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Total HT</th>
                                        <th class="text-end">Total TVA</th>
                                        <th class="text-end">Sous-total</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small class="text-muted">REF: {{ $item->product->sku ?? '—' }}</small><br>
                                            <div class="mt-1">
                                                @if($item->promo_type && $item->promo_value > 0)
                                                    <span class="badge bg-danger me-1" style="font-size: 0.7rem;">
                                                        <i class="bi bi-tag-fill me-1"></i>
                                                        @if($item->promo_type === 'percentage')
                                                            -{{ number_format($item->promo_value, 0) }}%
                                                        @else
                                                            -{{ number_format($item->promo_value, 2) }} MAD
                                                        @endif
                                                    </span>
                                                @endif
                                                <span class="badge bg-info bg-opacity-25" style="font-size: 0.7rem; color: #3498db;">TVA {{ number_format($item->tva_rate, 0) }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                        @if($order->type !== 'restock')
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
                                                            <span class="badge bg-danger mt-1 ms-auto" style="width:fit-content">-{{ number_format($item->promo_value, 0) }}%</span>
                                                        @else
                                                            <strong class="text-danger">{{ number_format($item->final_price_ht ?? ($item->price_unit_ht - $item->promo_value), 2, ',', ' ') }} MAD</strong>
                                                            <span class="badge bg-danger mt-1 ms-auto" style="width:fit-content">-{{ number_format($item->promo_value, 2) }} MAD</span>
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
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            @if($order->type !== 'restock')
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
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            @if($order->type !== 'restock')
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
                                                <span class="badge bg-{{ $delivery->status === 'livrer' ? 'success' : ($delivery->status === 'annuler' ? 'danger' : 'warning') }}">
                                                    {{ ['pending' => 'En attente', 'livrer' => 'Livrée', 'annuler' => 'Annulée'][$delivery->status] ?? $delivery->status }}
                                                </span>
                                            </td>
                                            <td>{{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') : '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.deliveries.show', $delivery) }}" class="btn btn-outline-custom btn-sm">
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
            @endif
        </div>
    </div>
@endsection

