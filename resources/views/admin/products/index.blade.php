@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Produits')
@section('page-subtitle', 'Catalogue des produits')

@section('topbar-actions')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouveau Produit</a>
@endsection

@section('content')
    <livewire:admin.product-index />

@endsection
