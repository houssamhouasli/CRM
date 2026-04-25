@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Validation du Retour #' . $return->id)
@section('page-subtitle', 'Retour créé le ' . $return->created_at->format('d/m/Y H:i'))

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Articles à Retourner</h5>
                <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} border border-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'validated' ? 'success' : 'danger') }} border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                    @if($return->status === 'pending') En attente
                    @elseif($return->status === 'validated') Validé
                    @else Rejeté @endif
                </span>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success m-3">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger m-3">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Produit</th>
                                <th class="text-center">Qté</th>
                                <th>État</th>
                                <th>Action Stock</th>
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
                                <td>
                                    @if($item->condition_type === 'unsold')
                                        <span class="text-success">→ Remis en stock</span>
                                    @elseif($item->condition_type === 'damaged')
                                        <span class="text-danger">→ Perte</span>
                                    @else
                                        <span class="text-warning">→ Stock périmé</span>
                                    @endif
                                </td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        @if($return->isPending())
        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.returns.validate', $return) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Valider ce retour? Les stocks seront mis à jour.')" class="btn btn-success">
                            <i class="bi bi-check-lg me-2"></i>Valider le retour
                        </button>
                    </form>

                    <form action="{{ route('admin.returns.reject', $return) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="reason" required placeholder="Motif du rejet" class="form-control">
                            <button type="submit" onclick="return confirm('Rejeter ce retour?')" class="btn btn-danger">
                                <i class="bi bi-x-lg me-2"></i>Rejeter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($return->validator)
        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Décision</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Traité par:</strong> {{ $return->validator->name }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ $return->validated_at->format('d/m/Y H:i') }}</p>
                @if($return->rejected_reason)
                    <p class="mb-0 text-danger"><strong>Motif du rejet:</strong> {{ $return->rejected_reason }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="card-header">
                <h5 class="mb-0">Informations Retour</h5>
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
                <div class="mb-0">
                    <label class="text-muted x-small d-block">Motif</label>
                    <span class="small">{{ $return->reason }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Livreur</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $return->livreur->name }}</strong></p>
                <p class="mb-0 small text-muted">{{ $return->livreur->email }}</p>
            </div>
        </div>


        <div class="data-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Dépôt</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $return->depot?->name ?? 'Non assigné' }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
