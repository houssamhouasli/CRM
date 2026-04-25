@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Détail du Retour #' . $return->id)
@section('page-subtitle', 'Retour créé le ' . $return->created_at->format('d/m/Y'))

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-box-arrow-in-left me-2 text-primary"></i>Articles Retournés</h5>
                <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} border border-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                    @if($return->status === 'pending') En attente
                    @elseif($return->status === 'validated') Validé
                    @else Rejeté @endif
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Produit</th>
                                <th class="text-center">Qté</th>
                                <th>État</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return->returnItems as $item)
                            <tr>
                                <td class="ps-4">{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->condition_type === 'unsold' ? 'success' : ($item->condition_type === 'damaged' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $item->condition_type === 'unsold' ? 'success' : ($item->condition_type === 'damaged' ? 'warning' : 'danger') }} border border-{{ $item->condition_type === 'unsold' ? 'success' : ($item->condition_type === 'damaged' ? 'warning' : 'danger') }} border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">
                                        @if($item->condition_type === 'unsold') Invendu
                                        @elseif($item->condition_type === 'damaged') Endommagé
                                        @else Périmé @endif
                                    </span>
                                </td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Return Info --}}
        <div class="data-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted x-small d-block">Livraison</label>
                    <span class="fw-bold">#{{ $return->delivery->id }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted x-small d-block">Client</label>
                    <span>{{ $return->delivery->order->client->company_name }}</span>
                </div>
                @if($return->reason)
                <div class="mb-3">
                    <label class="text-muted x-small d-block">Motif</label>
                    <span class="small">{{ $return->reason }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($return->validator)
        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Validation</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted x-small d-block">Traité par</label>
                    <span>{{ $return->validator->name }}</span>
                </div>
                <div class="mb-2">
                    <label class="text-muted x-small d-block">Date</label>
                    <span>{{ $return->validated_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($return->rejected_reason)
                <div class="mb-0">
                    <label class="text-muted x-small d-block">Motif du rejet</label>
                    <span class="small text-danger">{{ $return->rejected_reason }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('livreur.returns.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
