@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Commandes')
@section('page-subtitle', 'Commandes de votre région')

@section('content')
    <livewire:commercial.order-index />

@endsection
