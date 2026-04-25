@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Clients')
@section('page-subtitle', 'Gestion de tous les clients')

@section('topbar-actions') 
<a href="{{ route('admin.clients.create') }}" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus-lg me-1"></i>Nouveau Client</a>
@endsection

@section('content') 
    <livewire:admin.client-index />
@endsection
