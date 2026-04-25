@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Mes Livraisons')
@section('page-subtitle', 'Consultez et gérez vos bons de livraison assignés')

@section('content')
    <livewire:livreur.delivery-list />
@endsection
