@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Nouvelle Commande')
@section('page-subtitle', 'Saisie manuelle d\'une commande terrain pour un client')

@section('content')
    <div class="mb-3">
        <a href="{{ route('livreur.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux commandes
        </a>
    </div>

    @livewire('livreur.order-create')
@endsection
