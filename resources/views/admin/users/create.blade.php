@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-admin') @endsection
@section('page-title', 'Nouvel Utilisateur')
@section('page-subtitle', 'Gestion des utilisateurs')  

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-person-plus me-2"></i>Créer un utilisateur</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" id="role-select" class="form-select" required>
                            <option value="">— Sélectionner le rôle —</option>
                            @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dynamic Role Associations --}}
                    <div id="assoc-commercial" class="mb-3 d-none">
                        <label class="form-label">Région (pour Commercial)</label>
                        <select name="region_id" class="form-select">
                            <option value="">— Sélectionner —</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="assoc-livreur" class="mb-3 d-none">
                        <label class="form-label">Dépôt (pour Livreur)</label>
                        <select name="depot_id" class="form-select">
                            <option value="">— Sélectionner —</option>
                            @foreach($depots as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="assoc-depositaire" class="mb-3 d-none">
                        <label class="form-label">Dépôt Associé</label>
                        <select name="depot_id" class="form-select">
                            <option value="">— Sélectionner —</option>
                            @foreach($depots as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Créer</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const commDiv = document.getElementById('assoc-commercial');
        const livreurDiv = document.getElementById('assoc-livreur');
        const depotDiv = document.getElementById('assoc-depositaire');

        function toggleAssocs() {
            commDiv.classList.add('d-none');
            livreurDiv.classList.add('d-none');
            depotDiv.classList.add('d-none');

            // Disable all depot selects first
            livreurDiv.querySelector('select').disabled = true;
            depotDiv.querySelector('select').disabled = true;

            const val = roleSelect.value;
            if (val === 'commercial') commDiv.classList.remove('d-none');
            else if (val === 'livreur') {
                livreurDiv.classList.remove('d-none');
                livreurDiv.querySelector('select').disabled = false;
            }
            else if (val === 'depositaire') {
                depotDiv.classList.remove('d-none');
                depotDiv.querySelector('select').disabled = false;
            }
        }

        roleSelect.addEventListener('change', toggleAssocs);
        toggleAssocs();
    });
</script>
@endsection
