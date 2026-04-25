@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-livreur') @endsection
@section('title', 'Paramètres du Profil')
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gérez vos informations personnelles et la sécurité de votre compte')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2 text-primary"></i>Informations personnelles</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('livreur.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">NOM COMPLET</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold">ADRESSE EMAIL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-save me-1"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="data-card animate-in h-100" style="animation-delay: 0.1s;">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-warning"></i>Sécurité du compte</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('livreur.profile.password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">MOT DE PASSE ACTUEL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" name="current_password" class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror" required>
                        </div>
                        @error('current_password') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">NOUVEAU MOT DE PASSE</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" required>
                        </div>
                        @error('password') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold">CONFIRMATION</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-custom w-100">
                        <i class="bi bi-shield-check me-1"></i>Mettre à jour le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
