@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Commandes')
@section('page-subtitle', 'Toutes les commandes')

@section('content')
    <livewire:admin.order-index />

@endsection
