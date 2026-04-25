@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Commandes à Préparer')
@section('page-subtitle', 'Liste des commandes confirmées à charger')

@section('content')
    <livewire:depositaire.order-index />
@endsection
