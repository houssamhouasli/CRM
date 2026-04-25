@extends('layouts.app')
@section('sidebar') @include('partials.sidebar-regional') @endsection
@section('page-title', 'Modifier Client') 

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="data-card animate-in">
            <div class="card-header"><h5><i class="bi bi-pencil me-2"></i>{{ $client->company_name }}</h5></div>
            <div class="card-body">
                <form action="{{ route('commercial.clients.update', $client) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3"><label class="form-label">Nom de l'entreprise</label><input type="text" name="company_name" class="form-control" value="{{ old('company_name', $client->company_name) }}" required></div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Téléphone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Adresse</label><input type="text" name="address" class="form-control" value="{{ old('address', $client->address) }}"></div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
                        <a href="{{ route('commercial.clients.index') }}" class="btn btn-outline-custom">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
