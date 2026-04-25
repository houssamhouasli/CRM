@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Nouveau Client')
@section('page-subtitle', 'Gestion des clients') 

@section('content') 
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-building me-2"></i>Créer un client</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.clients.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom de l'entreprise</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Région</label>
                        <select name="region_id" class="form-select" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Créer</button>
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
