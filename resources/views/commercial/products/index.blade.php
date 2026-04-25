@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Catalogue Produits')
@section('page-subtitle', 'Liste des produits (lecture seule)')

@section('content')
    <livewire:commercial.product-index />

@endsection
