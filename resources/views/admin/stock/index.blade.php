@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Mouvements de Stock')
@section('page-subtitle', 'Historique des entrées et sorties')

@section('content')
    <livewire:admin.stock-movement-index />

@endsection
