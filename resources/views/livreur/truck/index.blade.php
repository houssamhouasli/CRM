@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Inventaire du Camion')
@section('page-subtitle', 'Consultez les produits actuellement chargés dans votre véhicule')

@section('content')
    <livewire:livreur.truck-inventory />
@endsection
