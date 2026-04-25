@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Nouvelle Région')
@section('page-subtitle', 'Créer une nouvelle région')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-geo-alt me-2"></i>Créer une région</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.regions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom de la région</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="ex: FES, CASA" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Créer</button>
                        <a href="{{ route('admin.regions.index') }}" class="btn btn-outline-custom"><i class="bi bi-arrow-left me-1"></i>Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
