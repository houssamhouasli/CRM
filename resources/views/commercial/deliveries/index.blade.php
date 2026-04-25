@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Livraisons de la Région')

@section('content')
    <livewire:commercial.delivery-index />
@endsection
