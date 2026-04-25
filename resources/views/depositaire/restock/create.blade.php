@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Nouvelle Demande de Stock')
@section('page-subtitle', 'Gérez votre panier de réapprovisionnement')

@section('content')
    <livewire:depositaire.restock-cart />
@endsection
