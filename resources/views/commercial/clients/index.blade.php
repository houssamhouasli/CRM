@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Clients')
@section('page-subtitle', 'Clients de votre région')



@section('content')
    <livewire:commercial.client-index />

@endsection
