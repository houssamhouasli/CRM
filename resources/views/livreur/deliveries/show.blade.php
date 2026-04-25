@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Détails du Bon de Livraison')
@section('page-subtitle', 'Consultez les produits et les informations du client')

@section('content')

@if($delivery->status === 'proposition' && $delivery->has_substitution)
<div class="row g-4">
    <div class="col-lg-8">
        
        <div class="p-3 rounded border mb-4 animate-in" style="background: rgba(255, 193, 7, 0.06); border-color: rgba(255, 193, 7, 0.3) !important;">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                <div>
                    <h5 class="mb-0 fw-bold text-warning">Proposition du Dépositaire</h5>
                    <small class="text-muted">Le dépositaire propose des modifications à cette commande. Présentez-les au client.</small>
                </div>
            </div>
        </div>

        
        <div class="data-card animate-in" style="animation-delay: 0.05s;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Comparaison Commande ↔ Proposition</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Produit</th>
                                <th class="text-center">Commandé</th>
                                <th class="text-center">Proposé</th>
                                <th class="text-center">Type</th>
                                <th class="text-end pe-4">Prix TTC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->items->where('is_substitution', false) as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold small">{{ $item->product->name }}</div>
                                    <small class="text-muted x-small">{{ $item->product->sku }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                        {{ $item->qty_ordered }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold {{ $item->qty_delivered < $item->qty_ordered ? 'text-warning' : 'text-success' }}">
                                        {{ $item->qty_delivered }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->qty_delivered < $item->qty_ordered)
                                        <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25 small">
                                            <i class="bi bi-dash-circle me-1"></i>Réduit
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-15 text-dark border border-success border-opacity-25 small">
                                            <i class="bi bi-check-circle me-1"></i>Conforme
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 fw-bold">{{ number_format($item->total_ttc ?? 0, 2, ',', ' ') }} MAD</td>
                            </tr>
                            @endforeach

                            @foreach($delivery->items->where('is_substitution', true) as $item)
                            <tr style="background: rgba(255, 193, 7, 0.04);">
                                <td class="ps-4">
                                    <div class="fw-bold small text-warning">
                                        <i class="bi bi-arrow-repeat me-1"></i>{{ $item->product->name }}
                                    </div>
                                    <small class="text-muted x-small">{{ $item->product->sku }} — <span class="text-warning">SUBSTITUTION</span></small>
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">—</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-warning">{{ $item->qty_delivered }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark small px-2 py-1">
                                        <i class="bi bi-plus-circle me-1"></i>Nouveau
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-bold text-warning">{{ number_format($item->total_ttc ?? 0, 2, ',', ' ') }} MAD</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid rgba(255, 193, 7, 0.3);">
                                <td colspan="4" class="text-end fw-bold" style="color: var(--primary);">TOTAL TTC Proposé</td>
                                <td class="text-end pe-4 fw-bold" style="color: var(--primary);">
                                    {{ number_format($delivery->total_ttc, 2, ',', ' ') }} MAD
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Client info --}}
        <div class="data-card animate-in" style="animation-delay: 0.1s;">
            <div class="card-header"><h5 class="mb-0">Client & Destination</h5></div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="ratio ratio-1x1 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center p-2" style="width: 50px;">
                        <i class="bi bi-shop fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold">{{ $delivery->order->client->company_name }}</h6>
                        <small class="text-muted d-block small">Commande #{{ $delivery->order_id }}</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="text-muted x-small d-block mb-1 text-uppercase fw-bold">Adresse de Livraison</label>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span class="small">{{ $delivery->order->client->address }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-muted x-small d-block mb-1 text-uppercase fw-bold">Contact</label>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        <span class="small">{{ $delivery->order->client->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Boutons d'action Accept/Reject --}}
        <div class="data-card mt-4 animate-in" style="animation-delay: 0.2s;">
            <div class="card-body text-center">
                <h6 class="fw-bold mb-3 text-warning">
                    <i class="bi bi-question-circle me-2"></i>Réponse du Client
                </h6>
                <p class="text-muted small mb-4">Présentez la proposition au client et enregistrez sa réponse.</p>

                <form action="{{ route('livreur.deliveries.accept-proposition', $delivery->id) }}" method="POST" 
                      onsubmit="return confirm('Le client ACCEPTE la proposition ? Les produits proposés seront ajoutés à la commande.');">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100 py-3 mb-3 fw-bold text-uppercase">
                        <i class="bi bi-check2-all me-2"></i>Client Accepte ✅
                    </button>
                </form>

                <form action="{{ route('livreur.deliveries.reject-proposition', $delivery->id) }}" method="POST"
                      onsubmit="return confirm('Le client REFUSE la proposition ? Seuls les produits originaux seront livrés, les substitutions retourneront au dépôt.');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-bold text-uppercase">
                        <i class="bi bi-x-circle me-2"></i>Client Refuse ❌
                    </button>
                </form>

                <form action="{{ route('livreur.deliveries.cancel', $delivery->id) }}" method="POST" class="mt-2"
                      onsubmit="return confirm('Annuler complètement cette livraison ?');">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm w-100 text-muted small">
                        <i class="bi bi-trash me-1"></i>Annuler la livraison
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ═══ NORMAL MODE (pending / livrer / annuler) ═══ --}}
@else
<form action="{{ route('livreur.deliveries.complete', $delivery->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous que cette commande a bien été livrée au client ?');">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="data-card animate-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Produits à Livrer</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 small">
                            {{ $delivery->items->count() }} Références
                        </span>
                        <span class="badge bg-{{ $delivery->status === 'pending' ? 'warning' : ($delivery->status === 'livrer' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $delivery->status === 'pending' ? 'warning' : ($delivery->status === 'livrer' ? 'success' : 'danger') }} border border-{{ $delivery->status === 'pending' ? 'warning' : ($delivery->status === 'livrer' ? 'success' : 'danger') }} border-opacity-25 px-2 py-1 small">
                            @if($delivery->status === 'pending') En attente
                            @elseif($delivery->status === 'livrer') Livrée
                            @else Annulée @endif
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Produit</th>
                                    <th class="text-center">Quantité Chargée</th>
                                    <th class="text-center" style="width: 150px;">Quantité Livrée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($delivery->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small">{{ $item->product->name }}</div>
                                        <small class="text-muted x-small lh-1">{{ $item->product->sku }}</small>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <span class="text-secondary">{{ $item->qty_delivered }}</span>
                                        <small class="text-muted ms-1">{{ $item->product->unit ?? 'Kg' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" 
                                               name="quantities[{{ $item->id }}]" 
                                               class="form-control form-control-sm text-center fw-bold border-primary border-opacity-25" 
                                               value="{{ $item->qty_delivered }}" 
                                               min="0" 
                                               max="{{ $item->qty_delivered }}"
                                               step="1"
                                               {{ $delivery->status !== 'pending' ? 'disabled' : '' }}
                                        >
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            @if($delivery->returns->count() > 0)
            <div class="data-card mt-3 animate-in" style="animation-delay: 0.15s;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-arrow-return-left me-2"></i>Retours associés</h5>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 small">
                        {{ $delivery->returns->count() }} Retour(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">N° Retour</th>
                                    <th class="text-center">Articles</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th class="pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($delivery->returns as $ret)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $ret->id }}</td>
                                    <td class="text-center">{{ $ret->returnItems->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ret->status === 'pending' ? 'warning' : ($ret->status === 'validated' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $ret->status === 'pending' ? 'warning' : ($ret->status === 'validated' ? 'success' : 'danger') }} border border-{{ $ret->status === 'pending' ? 'warning' : ($ret->status === 'validated' ? 'success' : 'danger') }} border-opacity-25 px-2 py-1 small">
                                            @if($ret->status === 'pending') En attente
                                            @elseif($ret->status === 'validated') Validé
                                            @else Rejeté @endif
                                        </span>
                                    </td>
                                    <td>{{ $ret->created_at->format('d/m/Y') }}</td>
                                    <td class="pe-4">
                                        <a href="{{ route('livreur.returns.show', $ret) }}" class="btn btn-outline-custom btn-sm">
                                            <i class="bi bi-eye me-1"></i>Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="data-card animate-in" style="animation-delay: 0.1s;">
                <div class="card-header">
                    <h5 class="mb-0">Client & Destination</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ratio ratio-1x1 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center p-2" style="width: 50px;">
                            <i class="bi bi-shop fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">{{ $delivery->order->client->company_name }}</h6>
                            <small class="text-muted d-block small">Commande #{{ $delivery->order_id }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-muted x-small d-block mb-1 text-uppercase fw-bold">Adresse de Livraison</label>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <span class="small">{{ $delivery->order->client->address }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted x-small d-block mb-1 text-uppercase fw-bold">Contact</label>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span class="small">{{ $delivery->order->client->phone }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span class="small">{{ $delivery->order->client->email }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-secondary bg-opacity-10 rounded border border-secondary border-opacity-10">
                        <label class="text-muted x-small d-block mb-1 text-uppercase fw-bold">Information Dépôt</label>
                        <div class="small fw-bold">{{ $delivery->depot->name ?? 'N/A' }}</div>
                        <small class="text-muted x-small text-italic">{{ $delivery->delivery_date ? 'Date prévue: ' . $delivery->delivery_date->format('d/m/Y') : 'Aucune date' }}</small>
                    </div>


                    @if(($delivery->total_ttc ?? 0) > 0)
                    <div class="mt-3 p-3 rounded border border-primary border-opacity-25" style="background: rgba(10,59,143,0.05);">
                        <label class="text-muted x-small d-block mb-2 text-uppercase fw-bold"><i class="bi bi-receipt me-1"></i>Montant Livraison</label>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Total HT</span>
                            <span class="fw-bold">{{ number_format($delivery->total_ht, 2, ',', ' ') }} MAD</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">TVA</span>
                            <span>{{ number_format($delivery->total_tva, 2, ',', ' ') }} MAD</span>
                        </div>
                        <div class="d-flex justify-content-between small pt-2 border-top border-primary border-opacity-25">
                            <span class="fw-bold" style="color:var(--primary);">Total TTC</span>
                            <span class="fw-bold" style="color:var(--primary);">{{ number_format($delivery->total_ttc, 2, ',', ' ') }} MAD</span>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="https://www.google.com/maps/search/{{ urlencode($delivery->order->client->address) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 py-2">
                            <i class="bi bi-map me-2"></i>Voir sur la carte
                        </a>
                    </div>
                </div>
            </div>

            @if($delivery->status === 'pending')
            <div class="data-card mt-4 animate-in" style="animation-delay: 0.2s;">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-success btn-sm w-100 py-3 mb-3 fw-bold text-uppercase">
                        <i class="bi bi-check2-all me-2"></i>Valider la Livraison
                    </button>
                    </form>
                    
                    <form action="{{ route('livreur.deliveries.cancel', $delivery->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler cette livraison ? Les produits resteront dans votre camion.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-bold text-uppercase">
                            <i class="bi bi-x-circle me-2"></i>Annuler la Livraison
                        </button>
                    </form>

                    <div class="small text-muted x-small mt-2">Assurez-vous que les quantités livrées correspondent à la réalité.</div>
                </div>
            </div>
            @else
            <div class="p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-10 text-center mt-3 animate-in" style="animation-delay: 0.2s;">
                <i class="bi bi-check-circle-fill text-success fs-4 d-block mb-1"></i>
                <span class="fw-bold text-success small">Livraison effectuée avec succès</span>
                <div class="x-small text-muted mb-2">{{ $delivery->updated_at->format('d/m/Y H:i') }}</div>
                <a href="{{ route('deliveries.print', $delivery) }}" class="btn btn-outline-primary btn-sm w-100 py-2 mb-2">
                    <i class="bi bi-printer me-2"></i>Imprimer BL (PDF)
                </a>
                <a href="{{ route('livreur.deliveries.returns.create', $delivery) }}" class="btn btn-outline-warning btn-sm w-100 py-2">
                    <i class="bi bi-arrow-return-left me-2"></i>Créer un Retour
                </a>
            </div>
@endif
        </div>
    </div>
@endif

@endsection
