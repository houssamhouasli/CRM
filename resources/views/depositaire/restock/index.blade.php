@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Demandes de Stock (Réapprovisionnement)')
@section('page-subtitle', 'Historique de vos commandes au siège')
@section('topbar-actions')
    <a href="{{ route('depositaire.restock.create') }}" class="btn btn-primary-custom shadow-sm fw-bold">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle Demande
    </a>
@endsection

@section('content')
<livewire:depositaire.restock-index />
@endsection
