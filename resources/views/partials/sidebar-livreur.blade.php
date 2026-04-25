{{-- Livreur Sidebar --}}
<div class="nav-section">
    <div class="nav-section-title">Principal</div>
    <a href="{{ route('livreur.dashboard') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('livreur.orders.index') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.orders.*') ? 'active' : '' }}">
        <i class="bi bi-cart"></i>
        <span>Mes Commandes</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Logistique</div>
    <a href="{{ route('livreur.deliveries.index') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.deliveries.*') ? 'active' : '' }}">
        <i class="bi bi-truck-flatbed"></i>
        <span>Mes Livraisons</span>
    </a>
    <a href="{{ route('livreur.returns.index') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.returns.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-return-left"></i>
        <span>Mes Retours</span>
        @php
            $pendingReturnsBadge = \App\Models\ReturnModel::where('livreur_id', auth()->id())->where('status', 'pending')->count();
        @endphp
        @if($pendingReturnsBadge > 0)
        <span class="badge bg-warning ms-auto" style="font-size: 0.6rem;">{{ $pendingReturnsBadge }}</span>
        @endif
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Équipement</div>
    <a href="{{ route('livreur.truck.index') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.truck.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i>
        <span>Mon Camion (Stock)</span>
    </a>
</div>

<div class="nav-section mt-auto mb-3">
    <div class="nav-section-title">Compte</div>
    <a href="{{ route('livreur.profile') }}" 
       class="nav-item-link {{ request()->routeIs('livreur.profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>Mon Profil</span>
    </a>
</div>
