@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-depositaire') @endsection
@section('page-title', 'Gestion des Retours')
@section('page-subtitle', 'Validation des retours de votre dépôt')

@section('content')
<livewire:depositaire.return-index />
@endsection
