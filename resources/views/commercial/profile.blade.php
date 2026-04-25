@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gérez vos informations personnelles et votre sécurité')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-person-circle me-2"></i>Informations personnelles</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('commercial.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Adresse Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-save me-1"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="data-card animate-in h-100">
            <div class="card-header">
                <h5><i class="bi bi-shield-lock me-2"></i>Sécurité du compte</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('commercial.profile.password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-custom">
                        <i class="bi bi-shield-check me-1"></i>Mettre à jour le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="data-card animate-in">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-3">
                    <i class="bi bi-info-circle text-primary fs-3"></i>
                </div>
                <h5>Information de compte</h5>
                <p class="text-muted mb-0">Rôle : <span class="badge bg-primary text-uppercase">{{ str_replace('_', ' ', $user->role) }}</span></p>
                <p class="text-muted small">Membre depuis le : {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
