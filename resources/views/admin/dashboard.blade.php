@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('page-title', 'Tableau de Bord')
@section('page-subtitle', 'Vue d\'ensemble en temps reel')

@section('topbar-actions')
<a href="{{ route('admin.export.all') }}" class="btn btn-primary-custom btn-sm">
    <i class="bi bi-download me-1"></i> Exportation de toutes les données 
</a>
@endsection

@section('content')

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Chiffre d'affaires</div>
                    <div class="stat-value">{{ number_format($totalRevenue, 2, ',', ' ') }} <small class="fs-6 text-muted">MAD</small></div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Commandes</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                </div>
                <div class="stat-icon blue"><i class="bi bi-cart-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card green animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Clients</div>
                    <div class="stat-value">{{ $totalClients }}</div>
                </div>
                <div class="stat-icon green"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange animate-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Produits Actifs</div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                </div>
                <div class="stat-icon orange"><i class="bi bi-box-seam-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card orange animate-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon orange"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-label">Commandes En attente</div>
                    <div class="stat-value fs-4">{{ $pendingOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue animate-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="stat-label">Commandes Confirmées</div>
                    <div class="stat-value fs-4">{{ $confirmedOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green animate-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="bi bi-truck"></i></div>
                <div>
                    <div class="stat-label">Commandes Livrées</div>
                    <div class="stat-value fs-4">{{ $deliveredOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card red animate-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
                <div>
                    <div class="stat-label">Commandes Annulées</div>
                    <div class="stat-value fs-4">{{ $canceledOrders }}</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart-fill me-2" style="color: var(--primary);"></i>Répartition des Ventes par Région</h5>
                <small class="text-muted">Ventes historiques totales</small>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div style="max-height: 300px; position: relative;">
                            <canvas id="regionSalesChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="table-responsive">
                            <table class="table table-dark-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Région</th>
                                        <th class="text-end">Ventes (TTC)</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = $regions->sum('total_sales') ?: 1;
                                    @endphp
                                    @foreach($regions as $index => $region)
                                    <tr>
                                        <td>
                                            <span class="badge rounded-circle me-2" style="background-color: {{ ['#0a3b8f', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#000000', '#41f63bff', '#54e6deff', '#ecd25cff', '#caef44ff', '#f65ce9ff'][$index % count($regions)] }}; width: 10px; height: 10px; display: inline-block; padding: 0;"></span>
                                            {{ $region->name }}
                                        </td>
                                        <td class="text-end fw-bold text-primary">{{ number_format($region->total_sales, 2, ',', ' ') }} MAD</td>
                                        <td class="text-end text-muted">{{ number_format(($region->total_sales / $grandTotal) * 100, 1) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="col-lg-4">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-geo-alt me-2" style="color: var(--primary);"></i>Performance Régionale</h5>
                <small class="text-muted">Mois en cours</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead><tr><th>Région</th><th class="text-end">Ventes </th></tr></thead>
                        <tbody>
                            @foreach($regions as $region)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-map text-primary small"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold small ">{{ $region->name }}</span>
                                            <small class="text-muted small">{{ $region->clients_count }} clients</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold {{ $region->monthly_revenue > 0 ? 'text-success' : 'text-muted opacity-50' }}">
                                    {{ number_format($region->monthly_revenue, 0, ',', ' ') }} MAD
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="data-card animate-in">
            <div class="card-header">
                <h5><i class="bi bi-clock-history me-2" style="color: var(--primary);"></i>Commandes Récentes</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom btn-sm">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th> 
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->client->company_name }}</td>
                                <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold">{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</td>
                                <td>
                                    <span class="badge-status badge-{{ $order->status }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Aucune commande</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-star-fill me-2" style="color: var(--accent);"></i>Meilleurs Produits</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Top 5</span>
            </div>
            <div class="card-body">
                @foreach($topProducts as $product)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="fw-bold d-block">{{ $product->name }}</span>
                                <small class="text-muted">{{ $product->category->name ?? 'Catégorie' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary">{{ number_format($product->total_sold, 0, ',', ' ') }}</span>
                                <small class="text-muted">{{ $product->unit }} vendus</small>
                            </div>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px; background: rgba(0,0,0,0.05); overflow: hidden;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: {{ $product->sales_percentage }}%; background: linear-gradient(90deg, var(--primary), var(--primary-light));" 
                                 aria-valuenow="{{ $product->sales_percentage }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colors = [
            '#0a3b8f',
            '#3b82f6',
            '#10b981',
            '#f59e0b',
            '#ef4444',
            '#8b5cf6',
            '#000000',
            '#41f63bff',
            '#54e6deff',
            '#ecd25cff',
            '#caef44ff',
            '#f65ce9ff',
        ];
        const ctx = document.getElementById('regionSalesChart').getContext('2d');
        const data = {
            labels: @json($regions->pluck('name')),
            datasets: [{
                data: @json($regions->pluck('total_sales')),
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 20
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#1e293b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 8,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
    });
</script>
@endpush
@endsection
