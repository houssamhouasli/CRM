@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Dashboard Régional')
@section('page-subtitle', 'Région : ' . auth()->user()->region->name)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Revenus</div><div class="stat-value">{{ number_format($totalRevenue, 2, ',', ' ') }} <small class="fs-6 text-muted">MAD</small></div></div>
                <div class="stat-icon primary"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Commandes</div><div class="stat-value">{{ $totalOrders }}</div></div>
                <div class="stat-icon blue"><i class="bi bi-cart-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card green animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Clients</div><div class="stat-value">{{ $totalClients }}</div></div>
                <div class="stat-icon green"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">En attente</div><div class="stat-value">{{ $pendingOrders }}</div></div>
                <div class="stat-icon orange"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div> 
    </div>
</div>

<div class="data-card animate-in">
    <div class="card-header">
        <h5><i class="bi bi-clock-history me-2"></i>Commandes Récentes</h5>
        <a href="{{ route('commercial.orders.index') }}" class="btn btn-outline-custom btn-sm">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover mb-0">
                <thead><tr><th>#</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->client->company_name }}</td>
                        <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</td>
                        <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td><a href="{{ route('commercial.orders.show', $order) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune commande</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
