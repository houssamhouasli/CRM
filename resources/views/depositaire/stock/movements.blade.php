@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Mouvements de Stock')
@section('page-subtitle', 'Historique complet des entrées et sorties de votre dépôt')

@section('content')
    <livewire:depositaire.stock-movement-index />
@endsection
