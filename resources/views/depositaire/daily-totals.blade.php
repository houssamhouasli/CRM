@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Récapitulatif des Livraisons')
@section('page-subtitle', 'Consulter les totaux par livreur et le total général pour une journée spécifique.')

@section('content')
<div class="row g-3 mb-4 animate-in d-print-none">
    <div class="col-12">
        <div class="data-card shadow-sm p-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%); border-radius: 16px;">
            <form action="{{ route('depositaire.daily-totals') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label fw-bold text-dark mb-2">Choisir une date :</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar3 text-primary"></i></span>
                        <input type="date" id="date" name="date" class="form-control border-start-0 ps-0" value="{{ $date }}" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <button type="button" onclick="window.print()" class="btn btn-outline-dark shadow-sm">
                        <i class="bi bi-printer me-2"></i>Imprimer le Rapport
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue animate-in">
            <div class="stat-label">Bons de Livraison (Livrés)</div>
            <div class="stat-value">{{ $summary['total_deliveries'] }}</div>
            <div class="stat-icon blue"><i class="bi bi-file-earmark-check-fill"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green animate-in" style="animation-delay: 0.1s;">
            <div class="stat-label">Total Produits Livrés</div>
            <div class="stat-value">{{ number_format($summary['total_items'], 0, ',', ' ') }}</div>
            <div class="stat-icon green"><i class="bi bi-box-seam-fill"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card indigo animate-in" style="animation-delay: 0.2s;">
            <div class="stat-label">Valeur Totale (TTC)</div>
            <div class="stat-value">{{ number_format($summary['total_value_ttc'], 2, ',', ' ') }} <small class="fs-6">DH</small></div>
            <div class="stat-icon indigo"><i class="bi bi-cash-stack"></i></div>
        </div>
    </div>
</div>

<h4 class="mb-3 fw-bold animate-in" style="animation-delay: 0.3s;">
    <i class="bi bi-person-lines-fill me-2 text-primary"></i>Détail par Livreur
</h4>

@forelse($livreurBreakdown as $livreurId => $data)
<div class="data-card mb-4 animate-in" style="animation-delay: 0.4s;">
    <div class="card-header bg-primary bg-opacity-10 py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h5 class="mb-0 fw-bold text-primary">{{ $data['name'] }} <span class="ms-2 badge bg-primary bg-opacity-25 text-primary small">{{ $data['delivery_count'] }} Bon(s)</span></h5>
        </div>
        <div class="text-end">
            <span class="text-muted small">Total Livré :</span>
            <span class="fw-bold text-dark">{{ number_format($data['total_ttc'], 2, ',', ' ') }} DH</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Référence</th>
                        <th>Produit</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end pe-4">Valeur TTC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['products'] as $product)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $product['sku'] ?? '—' }}</td>
                        <td class="fw-bold">{{ $product['name'] }}</td>
                        <td class="text-center">
                            <span class="fw-bold text-dark">{{ number_format($product['quantity'], 0, ',', ' ') }}</span>
                            <span class="text-muted small ms-1">{{ $product['unit'] }}</span>
                        </td>
                        <td class="text-end pe-4 text-dark">{{ number_format($product['total_ttc'], 2, ',', ' ') }} DH</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="data-card text-center py-5 mb-4 animate-in" style="animation-delay: 0.4s;">
    <i class="bi bi-info-circle text-muted fs-2 mb-3 d-block"></i>
    <p class="text-secondary">Aucun livreur n'a effectué de livraison à cette date.</p>
</div>
@endforelse

<div class="data-card animate-in border-primary border-2" style="animation-delay: 0.5s; border: 2px solid var(--primary) !important;">
    <div class="card-header bg-dark text-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-calculator me-2 text-warning"></i>
            TOTAL GÉNÉRAL DU DÉPÔT
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-dark-custom mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="ps-4">Référence</th>
                        <th>Produit</th>
                        <th class="text-center">Quantité Cumulée</th>
                        <th class="text-end pe-4">Valeur TTC Cumulée</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($totals as $item)
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-dark font-monospace">{{ $item->product_sku ?? '—' }}</span></td>
                        <td class="fw-bold">{{ $item->product_name }}</td>
                        <td class="text-center">
                            <span class="fs-5 fw-bold text-primary">{{ number_format($item->total_quantity, 0, ',', ' ') }}</span>
                            <span class="text-muted small ms-1">{{ $item->product_unit }}</span>
                        </td>
                        <td class="text-end pe-4 fw-bold text-success">{{ number_format($item->total_ttc, 2, ',', ' ') }} DH</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">Aucune donnée cumulative.</td></tr>
                    @endforelse
                </tbody>
                @if($totals->isNotEmpty())
                <tfoot class="bg-dark text-white">
                    <tr>
                        <th colspan="2" class="ps-4 py-3">TOTAL GÉNÉRAL</th>
                        <th class="text-center fs-4 py-3 text-warning">{{ number_format($summary['total_items'], 0, ',', ' ') }} Items</th>
                        <th class="text-end pe-4 fs-4 py-3 text-warning">{{ number_format($summary['total_value_ttc'], 2, ',', ' ') }} DH</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-sm { width: 32px; height: 32px; font-size: 14px; }
    @media print {
        body { background: white !important; font-size: 12pt !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .sidebar, .topbar, form, .btn, .d-print-none { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .data-card { box-shadow: none !important; border: 1px solid var(--glass-border) !important; margin-bottom: 20px !important; break-inside: avoid; }
        .animate-in { animation: none !important; transform: none !important; opacity: 1 !important; }
        .bg-primary.bg-opacity-10 { background-color: rgba(10, 59, 143, 0.1) !important; }
        .tfoot.bg-dark { background-color: #0e1e3a !important; color: #fff !important; }
        .stat-card { border: 1px solid var(--glass-border) !important; margin-bottom: 20px !important; }
        .stat-icon { -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
