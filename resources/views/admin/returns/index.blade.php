@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Gestion des Retours')
@section('page-subtitle', 'Validation et suivi des retours clients')

@section('content')
    <livewire:admin.return-list />
@endsection
 