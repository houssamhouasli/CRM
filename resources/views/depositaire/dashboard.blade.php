@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Tableau de Bord Dépôt')
@section('page-subtitle', auth()->user()->depot->name ?? 'Gestion de Stock & Expéditions')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card blue animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Commandes à Préparer</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                </div>
                <div class="stat-icon blue"><i class="bi bi-cart-check-fill"></i></div>
            </div>
            <a href="{{ route('depositaire.orders.index') }}" class="btn btn-link px-0 mt-3 text-blue text-decoration-none small fw-bold">
                Voir toutes <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card green animate-in" style="animation-delay: 0.1s;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Livraisons du Dépôt</div>
                    <div class="stat-value">{{ $totalDeliveries }}</div>
                </div>
                <div class="stat-icon green"><i class="bi bi-truck-flatbed"></i></div>
            </div>
            <a href="{{ route('depositaire.deliveries.index') }}" class="btn btn-link px-0 mt-3 text-green text-decoration-none small fw-bold">
                Voir l'historique <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="stat-card {{ $lowStockProducts->count() > 0 ? 'red' : 'green' }} animate-in" style="animation-delay: 0.2s;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Alertes Stock</div>
                    <div class="stat-value">{{ $lowStockProducts->count() }}</div>
                </div>
                <div class="stat-icon {{ $lowStockProducts->count() > 0 ? 'red' : 'green' }}">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
            </div>
            <a href="{{ route('depositaire.stock.index') }}" class="btn btn-link px-0 mt-3 text-{{ $lowStockProducts->count() > 0 ? 'danger' : 'success' }} text-decoration-none small fw-bold">
                Gérer le stock <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Livraisons Récentes</h5>
                <a href="{{ route('depositaire.deliveries.index') }}" class="btn btn-outline-custom btn-sm">Tout voir</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Bon #</th>
                                <th>Client</th>
                                <th>Livreur</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDeliveries as $del)
                            <tr>
                                <td><strong>#{{ $del->id }}</strong></td>
                                <td>{{ $del->order->client->company_name ?? 'N/A' }}</td>
                                <td>{{ $del->livreur->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $del->status === 'completed' ? 'success' : ($del->status === 'partial' ? 'info' : 'warning') }} bg-opacity-10 text-{{ $del->status === 'completed' ? 'success' : ($del->status === 'partial' ? 'info' : 'warning') }} py-1 px-2 border border-{{ $del->status === 'completed' ? 'success' : ($del->status === 'partial' ? 'info' : 'warning') }} border-opacity-25">
                                        {{ $del->status === 'completed' ? 'Livrée' : ($del->status === 'partial' ? 'Partielle' : 'En attente') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Aucune livraison récente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-warning"></i>Stocks Faibles</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($lowStockProducts as $product)
                    <div class="list-group-item bg-transparent border-0 px-0 d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <span class="text-dark d-block small fw-bold">{{ $product->name }}</span>
                            <small class="text-muted small">REF: {{ $product->sku ?? '—' }}</small>
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $product->depotStocks()->where('depot_id', auth()->user()->depot_id)->first()->quantity ?? 0 }} {{ $product->unit }}</span>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-check2-circle text-success fs-2 mb-2 d-block"></i>
                        <p class="text-secondary small mb-0">Tous vos stocks sont au-dessus du seuil d'alerte.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
