@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Bons de Livraison')
@section('page-subtitle', 'Historique des livraisons de votre dépôt')

@section('content')
<livewire:depositaire.delivery-index />
@endsection
