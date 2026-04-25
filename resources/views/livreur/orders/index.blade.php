@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Mes Commandes Terrain')
@section('page-subtitle', 'Consultez les commandes que vous avez saisies')
@section('topbar-actions')
<a href="{{ route('livreur.orders.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouvelle Commande</a>
@endsection

@section('content')
    <livewire:livreur.order-index />
@endsection
