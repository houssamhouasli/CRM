@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('title', 'Tableau de Bord Livreur')
@section('page-title', 'Tableau de Bord')
@section('page-subtitle', 'Consultez vos livraisons et l\'état de votre véhicule')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Livraisons</div>
                    <div class="stat-value">{{ $deliveriesCount }}</div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-truck"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Livraisons en cours</div>
                    <div class="stat-value">{{ $pendingCount }}</div>
                </div>
                <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card green animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Livraisons terminées</div>
                    <div class="stat-value">{{ $completedCount }}</div>
                </div>
                <div class="stat-icon green"><i class="bi bi-check2-all"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card purple animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Mes Retours</div>
                    <div class="stat-value">{{ $returnsCount }}</div>
                    @if($pendingReturnsCount > 0)
                    <div class="mt-1">
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25" style="font-size: 0.65rem;">
                            {{ $pendingReturnsCount }} en attente
                        </span>
                    </div>
                    @endif
                </div>
                <div class="stat-icon purple"><i class="bi bi-arrow-return-left"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-lg-8">
        <div class="data-card animate-in" style="animation-delay: 0.2s;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Livraisons à Effectuer <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 ms-2" style="font-size: 0.7rem;">{{ $pendingCount }} Missions</span></h5>
                <a href="{{ route('livreur.deliveries.index') }}" class="btn btn-sm btn-outline-custom">Tableau complet</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Bon #</th>
                                <th>Client</th>
                                <th>Date Prévue</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingDeliveries as $del)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $del->id }}</td>
                                <td>
                                    <div class="fw-bold small">{{ $del->order->client->company_name }}</div>
                                    <small class="text-muted x-small">Commande #{{ $del->order_id }}</small>
                                </td>
                                <td><small>{{ $del->delivery_date ? $del->delivery_date->format('d/m/Y') : 'N/A' }}</small></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('livreur.deliveries.show', $del->id) }}" class="btn btn-custom border-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small">Aucune livraison en attente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="data-card animate-in" style="animation-delay: 0.3s;">
            <div class="card-header">
                <h5 class="mb-0">Stock du Camion <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 ms-2" style="font-size: 0.7rem;">TOP 5</span></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3"><small>Produit</small></th>
                                <th class="text-center"><small>Stock</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($truckStocks as $stock)
                            <tr>
                                <td class="ps-3">
                                    <small class="fw-bold d-block">{{ $stock->product->name }}</small>
                                    <small class="text-muted x-small lh-1">{{ $stock->product->sku }}</small>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $stock->quantity > 20 ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $stock->quantity > 20 ? 'success' : 'warning' }} border border-{{ $stock->quantity > 20 ? 'success' : 'warning' }} border-opacity-25 px-2 py-0" style="font-size: 0.65rem;">
                                        {{ $stock->quantity }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted small">Camion vide ou non assigné.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($truckStocks->count() > 0)
                <div class="card-footer bg-transparent border-top border-color py-2 text-center">
                    <a href="{{ route('livreur.truck.index') }}" class="text-decoration-none small text-primary fw-bold">Détails du Camion <i class="bi bi-arrow-right-short"></i></a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
