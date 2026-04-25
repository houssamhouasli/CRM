@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', $client->company_name)

@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="data-card animate-in mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Informations</h5>
                <a href="{{ route('commercial.clients.edit', $client) }}" class="btn btn-outline-custom btn-sm">
                    <i class="bi bi-pencil me-1"></i> Modifier
                </a>
            </div>
            <div class="card-body">
                <table class="table table-dark-custom table-borderless mb-0">
                    <tr><td style="width:120px;">Entreprise</td><td><strong>{{ $client->company_name }}</strong></td></tr>
                    <tr><td >Email</td><td>{{ $client->email }}</td></tr>
                    <tr><td >Téléphone</td><td>{{ $client->phone ?? '—' }}</td></tr>
                    <tr><td >Adresse</td><td>{{ $client->address ?? '—' }}</td></tr>
                    <tr><td >Région</td><td><span class="badge bg-info">{{ $client->region->name }}</span></td></tr>
                </table>
            </div>
        </div>

    </div>
    <div class="col-lg-7">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-cart me-2"></i>Commandes récentes</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead><tr><th>#</th><th>Date</th><th>Montant</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($client->orders as $order)
                            <tr> 
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</td>
                                <td><span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : ($order->status === 'validated' ? 'info' : 'danger')) }}">
                                    {{ $order->status === 'pending' ? 'En attente' : ($order->status === 'delivered' ? 'Livrée' : ($order->status === 'validated' ? 'Validée' : 'Annulée')) }}
                                </span></td>
                                <td><a href="{{ route('commercial.orders.show', $order) }}" class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Aucune commande</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot><tr style="border-top:2px solid var(--primary);"><td colspan="3" class="text-end"><strong>Total</strong></td><td class="text-end"><strong>{{ number_format($client->orders()->sum('total_ttc'), 2, ',', ' ') }} MAD</strong></td></tr></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
