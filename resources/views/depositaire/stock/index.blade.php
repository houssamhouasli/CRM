@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Gestion du Dépôt')
@section('page-subtitle', 'État actuel des produits dans votre dépôt')

@section('content')
    <livewire:depositaire.stock-index />
@endsection
