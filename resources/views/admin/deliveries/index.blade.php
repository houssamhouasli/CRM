@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('title', 'Gestion des Livraisons')
@section('page-title', 'Livraisons')
@section('page-subtitle', 'Liste de toutes les livraisons (Camions/Dépôts)')

@section('content')
    @livewire('admin.delivery-index')
@endsection
