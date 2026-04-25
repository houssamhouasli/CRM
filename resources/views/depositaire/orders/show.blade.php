@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Commande #' . $order->id)
@section('page-subtitle', $order->client->company_name)
 
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="data-card animate-in mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-info-circle me-2"></i>Informations Commande</h5>
                <a href="{{ route('orders.print', $order) }}" class="btn btn-outline-primary btn-sm shadow-sm">
                    <i class="bi bi-file-pdf me-1"></i>Télécharger PDF
                </a>
            </div>
            <div class="card-body">
                <table class="table table-dark-custom table-borderless mb-0">
                    <tr><td class="text-muted">Client</td><td><strong>{{ $order->client->company_name }}</strong></td></tr>
                    <tr><td class="text-muted">Région</td><td>{{ $order->client->region->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Date Commande</td><td>{{ $order->order_date->format('d/m/Y H:i') }}</td></tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            <span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @php
            $hasRemaining = false;
            foreach($order->items as $item) {
                if ($item->delivered_quantity < $item->quantity) {
                    $hasRemaining = true;
                    break;
                }
            }
        @endphp

        @if($order->status === 'pending' && $hasRemaining)
        <div class="data-card animate-in mb-3 border border-primary border-opacity-25">
            <div class="card-header bg-primary bg-opacity-10"><h5><i class="bi bi-plus-circle me-2"></i>Actions</h5></div>
            <div class="card-body">
                <p class="text-muted small">Cette commande est en attente. Créez une livraison pour la confirmer.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('depositaire.orders.delivery.create', $order) }}" class="btn btn-primary-custom shadow-sm fw-bold">
                        <i class="bi bi-truck me-2"></i>Générer une Livraison
                    </a>
                    <form action="{{ route('depositaire.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler toute la commande ?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>Annuler la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @elseif($order->status === 'confirmed' && $hasRemaining)
        <div class="data-card animate-in mb-3 border border-warning border-opacity-25">
            <div class="card-header bg-warning bg-opacity-10"><h5><i class="bi bi-plus-circle me-2"></i>Livraison Partielle</h5></div>
            <div class="card-body">
                <p class="text-muted small">Des produits restent à livrer. Créez une nouvelle livraison pour les articles manquants.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('depositaire.orders.delivery.create', $order) }}" class="btn btn-primary-custom shadow-sm fw-bold">
                        <i class="bi bi-truck me-2"></i>Compléter la Livraison
                    </a>
                </div>
            </div>
        </div>
        @elseif(!$hasRemaining)
        <div class="data-card animate-in mb-3 border border-success border-opacity-25">
            <div class="card-header bg-success bg-opacity-10"><h5><i class="bi bi-check-circle me-2"></i>Livraison Complète</h5></div>
            <div class="card-body">
                <p class="text-muted small">Tous les articles ont été livrés. Aucune action requise.</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-list-ul me-2"></i>Articles à Préparer</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-center">Quantité commandée</th>
                                <th class="text-center">Déjà livrée</th>
                                <th class="text-center">Reste à livrer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-2 d-none d-sm-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,0.05) !important;">
                                            <i class="bi bi-box-seam text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block">{{ $item->product->name }}</span>
                                            <small class="text-muted">{{ $item->product->sku ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                <td class="text-center text-success">{{ $item->delivered_quantity }} {{ $item->product->unit }}</td>
                                <td class="text-center text-warning">
                                    {{ max(0, $item->quantity - $item->delivered_quantity) }} {{ $item->product->unit }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="data-card animate-in mt-3">
            <div class="card-header"><h5><i class="bi bi-truck me-2"></i>Bons de Livraison rattachés</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Bon #</th>
                                <th>Livreur</th>
                                <th>Statut</th>
                                <th>Date prévue</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->deliveries as $delivery)
                            <tr>
                                <td><strong>#{{ $delivery->id }}</strong></td>
                                <td>{{ $delivery->livreur->name ?? '—' }}</td>
                                <td>
                                    @php
                                        $sClass = ['pending'=>'warning','proposition'=>'info','livrer'=>'success','annuler'=>'danger'][$delivery->status] ?? 'secondary';
                                        $sLabel = ['pending'=>'En attente','proposition'=>'⚡ Proposition','livrer'=>'Livrée','annuler'=>'Annulée'][$delivery->status] ?? $delivery->status;
                                    @endphp
                                    <span class="badge bg-{{ $sClass }}">{{ $sLabel }}</span>
                                </td>
                                <td>{{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') : '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('depositaire.deliveries.show', $delivery) }}" class="btn btn-outline-custom btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Aucune livraison enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
