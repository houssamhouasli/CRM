@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Détails Demande de Stock #' . $order->id)
@section('page-subtitle', 'Consultez les produits demandés à la société mère')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="data-card animate-in h-100">
            <div class="card-header border-bottom border-secondary border-opacity-25">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Infos Demande</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block">Identifiant</label>
                    <span class="fw-bold">#{{ $order->id }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Date</label>
                    <span class="fw-bold">{{ $order->order_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Statut Actuel</label>
                    @php
                        $statusClass = [
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                        ][$order->status] ?? 'secondary';
                        
                        $statusLabel = [
                            'pending' => 'En attente de validation',
                            'confirmed' => 'Confirmée/Expédiée par le siège',
                            'delivered' => 'Réceptionnée (Stock mis à jour)',
                            'cancelled' => 'Annulée',
                        ][$order->status] ?? $order->status;
                    @endphp
                    <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} py-1 px-2 border border-{{ $statusClass }} border-opacity-25 mt-1">
                        {{ $statusLabel }}
                    </span>
                </div>

                @if($order->status === 'confirmed')
                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <form action="{{ route('depositaire.restock.receive', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm" onclick="return confirm('Attention : Cette action augmentera définitivement votre stock local. Confirmez-vous avoir reçu ces produits ?')">
                                <i class="bi bi-check2-circle me-2"></i>Confirmer la Réception
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="data-card animate-in h-100" style="animation-delay: 0.1s;">
            <div class="card-header border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-warning"></i>Détails des Produits</h5>
                <a href="{{ route('depositaire.restock.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-center">Quantité Commandée</th>
                                <th>Unité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-primary">{{ $item->product->name }}</div>
                                    <small class="text-muted">REF: {{ $item->product->sku ?? '—' }}</small>
                                </td>
                                <td class="text-center fw-bold text-primary">{{ number_format($item->quantity, 0) }}</td>
                                <td><small class="text-muted">{{ $item->product->unit }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
