@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', $client->company_name)
@section('page-subtitle', 'Détails du client')

@section('content')
    <div class="row g-3">

        <div class="col-lg-5">
            <div class="data-card animate-in mb-3">
                <div class="card-header">
                    <h5><i class="bi bi-building me-2"></i>Informations</h5>
                </div>
                <div class="card-body">
                    <table class="table table-dark-custom table-borderless mb-0">
                        <tr>
                            <td style="width:120px;">Entreprise</td>
                            <td><strong>{{ $client->company_name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $client->email }}</td>
                        </tr>
                        <tr>
                            <td>Téléphone</td>
                            <td>{{ $client->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Adresse</td>
                            <td>{{ $client->address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Région</td>
                            <td><span class="badge bg-info bg-opacity-25" style="color:#5dade2;">{{ $client->region->name }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>



        </div>


        <div class="col-lg-7">
            <div class="data-card animate-in">
                <div class="card-header">
                    <h5><i class="bi bi-cart me-2"></i>Commandes récentes</h5>
                    <form action="{{ route('admin.clients.show', $client) }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="status" class="form-select" onchange="this.form.submit()" style="width: 200px;">
                                        <option value="">Tous les statuts</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validée</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Terminée</option>
                                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</td>
                                        <td><span
                                                class="badge-status badge-{{ $order->status }}">{{ $order->status === 'pending' ? 'En attente' : $order->status }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.orders.show', $order) }}"
                                                class="btn btn-outline-custom btn-sm"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Aucune commande</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center py-2">
                                        {{ $orders->links() }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection