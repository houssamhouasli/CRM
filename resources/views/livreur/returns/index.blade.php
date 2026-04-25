@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('page-title', 'Mes Retours')
@section('page-subtitle', 'Liste de vos demandes de retour')

@section('content')
    <livewire:livreur.return-list />
@endsection
