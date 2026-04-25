@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', $product->name)
@section('page-subtitle', 'Détails du produit et historique de stock (Dépôt)')

@section('content')
<div class="row g-3">

    <div class="col-lg-5">
        <div class="data-card animate-in h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-box-seam me-2"></i>Fiche Produit</h5>
                <span class="badge bg-secondary">{{ $product->category->name }}</span>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center py-4 bg-dark bg-opacity-10 rounded-3">
                    <h3 class="fw-bold text-dark mb-2">{{ $product->name }}</h3>
                    <div class="fs-2 fw-bold" style="color: var(--primary-light);">{{ number_format($product->price_ht, 2, ',', ' ') }} DH <small class="fs-4 text-muted font-normal">(HT)</small></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-custom table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 140px;">SKU / REF</td>
                            <td><strong class="text-primary">{{ $product->sku ?? '—' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catégorie</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Unité de mesure</td>
                            <td>{{ $product->unit ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">TVA (%)</td>
                            <td><span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10">{{ number_format($product->tva_rate, 0, ',', ' ') }}%</span></td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <h6 class="mb-0">Note de Gestion</h6>
                    </div>
                    <p class="text-muted small italic">Ces informations sont consultatives pour votre dépôt. Contactez l'administrateur pour toute correction de catalogue.</p>
                </div>

                <div class="mt-4">
                    <a href="{{ route('depositaire.stock.index') }}" class="btn btn-outline-custom w-100">
                        <i class="bi bi-arrow-left me-2"></i>Retour au stock
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="data-card animate-in h-100" style="animation-delay: 0.1s;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-activity me-2"></i>Historique des Mouvements (Votre Dépôt)</h5>
                <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-2 px-3">
                    <i class="bi bi-building me-1"></i> {{ auth()->user()->depot->name }}
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date / Heure</th>
                                <th>Type</th>
                                <th class="text-center">Quantité</th>
                                <th>Raison / Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $move)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock-history me-2 text-muted small"></i>
                                        <small>{{ $move->moved_at ? $move->moved_at->format('d/m/Y H:i') : '—' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $typeClass = $move->type === 'IN' ? 'success' : 'danger';
                                        $typeLabel = $move->type === 'IN' ? 'ENTRÉE' : 'SORTIE';
                                    @endphp
                                    <span class="badge bg-{{ $typeClass }} bg-opacity-10 text-{{ $typeClass }} py-1 px-2 border border-{{ $typeClass }} border-opacity-25" style="font-size: 0.7rem;">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold {{ $move->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                    {{ $move->type === 'IN' ? '+' : '-' }}{{ number_format($move->quantity, 0) }}
                                </td>
                                <td><small class="text-muted">{{ $move->reason ?? '—' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-slash-circle d-block mb-2 fs-4"></i>
                                    Aucun mouvement enregistré pour ce produit dans votre dépôt.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
