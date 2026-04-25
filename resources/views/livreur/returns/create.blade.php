@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Créer un Retour')
@section('page-subtitle', 'Livraison #' . $delivery->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-return-left me-2 text-primary"></i>Articles à retourner</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('livreur.deliveries.returns.store', $delivery) }}" method="POST">
                    @csrf

                    {{-- Reason --}}
                    <div class="mb-4">
                        <label for="reason" class="form-label">Motif du retour (optionnel)</label>
                        <textarea name="reason" id="reason" rows="2" class="form-control"
                            placeholder="Ex: Client absent, produits non vendus..."></textarea>
                    </div>

                    {{-- Items Table --}}
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Livré</th>
                                    <th class="text-center">Déjà retourné</th>
                                    <th class="text-center" style="width: 120px;">Qté à retourner</th>
                                    <th>État</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableItems as $index => $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold small">{{ $item['product_name'] }}</div>
                                        <input type="hidden" name="items[{{ $index }}][delivery_item_id]" value="{{ $item['delivery_item_id'] }}">
                                    </td>
                                    <td class="text-center">{{ $item['qty_delivered'] }} {{ $item['unit'] }}</td>
                                    <td class="text-center">{{ $item['returned_quantity'] }} {{ $item['unit'] }}</td>
                                    <td class="text-center">
                                        <input type="number" name="items[{{ $index }}][quantity]"
                                            min="0" max="{{ $item['returnable_quantity'] }}" value="0"
                                            class="form-control form-control-sm text-center">
                                        <small class="text-muted">max: {{ $item['returnable_quantity'] }}</small>
                                    </td>
                                    <td>
                                        <select name="items[{{ $index }}][condition_type]" class="form-select form-select-sm">
                                            <option value="unsold">Invendu</option>
                                            <option value="damaged">Endommagé</option>
                                            <option value="expired">Périmé</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][notes]"
                                            class="form-control form-control-sm" placeholder="Notes...">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('livreur.deliveries.show', $delivery) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-2"></i>Créer le retour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Delivery Info --}}
        <div class="data-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-truck me-2 text-primary"></i>Informations Livraison</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted x-small d-block">Client</label>
                    <span class="fw-bold">{{ $delivery->order->client->company_name }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted x-small d-block">Date</label>
                    <span>{{ $delivery->delivery_date?->format('d/m/Y') ?? $delivery->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="mb-0">
                    <label class="text-muted x-small d-block">Adresse</label>
                    <span class="small">{{ $delivery->order->client->address }}</span>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Légende des états</h6>
            </div>
            <div class="card-body">
                <div class="small mb-2">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Invendu</span>
                    <span class="text-muted">→ Remis en stock dépôt</span>
                </div>
                <div class="small mb-2">
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Endommagé</span>
                    <span class="text-muted">→ Perte (pas de remise en stock)</span>
                </div>
                <div class="small mb-0">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Périmé</span>
                    <span class="text-muted">→ Stock périmé</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
