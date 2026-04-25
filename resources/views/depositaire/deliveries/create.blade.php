@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Préparation de Livraison')
@section('page-subtitle', 'Commande #' . $order->id . ' - ' . ($order->client->company_name ?? 'Client'))

@section('content')
    <livewire:depositaire.create-delivery :order="$order" />
@endsection
