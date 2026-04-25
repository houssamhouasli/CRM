{{-- Depositaire Sidebar --}}
<div class="nav-section">
    <div class="nav-section-title">Principal</div>
    <a href="{{ route('depositaire.dashboard') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Opérations Dépôt</div>
    <a href="{{ route('depositaire.orders.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.orders.*') && !request()->routeIs('depositaire.restock.*') ? 'active' : '' }}">
        <i class="bi bi-cart-check-fill"></i>
        <span>Commandes Clients</span>
    </a>

    <a href="{{ route('depositaire.restock.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.restock.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-repeat"></i>
        <span>Demandes de Stock</span>
    </a>

    <a href="{{ route('depositaire.deliveries.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.deliveries.*') ? 'active' : '' }}">
        <i class="bi bi-truck"></i>
        <span>Livraisons</span>
    </a>

    <a href="{{ route('depositaire.daily-totals') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.daily-totals') ? 'active' : '' }}">
        <i class="bi bi-calculator"></i>
        <span>Totaux Journaliers</span>
    </a>

    <a href="{{ route('depositaire.returns.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.returns.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-return-left"></i>
        <span>Retours</span>
        @php
            $depositaireId = auth()->user()->depot_id;
            $depotPendingReturns = $depositaireId ? \App\Models\ReturnModel::where('depot_id', $depositaireId)->where('status', 'pending')->count() : 0;
        @endphp
        @if($depotPendingReturns > 0)
        <span class="badge bg-warning ms-auto" style="font-size: 0.6rem;">{{ $depotPendingReturns }}</span>
        @endif
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Inventaire & Dépôt</div>
    <a href="{{ route('depositaire.stock.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.stock.index') || request()->routeIs('depositaire.products.show') ? 'active' : '' }}">
        <i class="bi bi-building-fill"></i>
        <span>Gestion du Dépôt</span>
    </a>

    <a href="{{ route('depositaire.stock-movements.index') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.stock-movements.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>Mouvements de Stock</span>
    </a>
</div>

<div class="nav-section mt-auto mb-3">
    <div class="nav-section-title">Compte</div>
    <a href="{{ route('depositaire.profile') }}" 
       class="nav-item-link {{ request()->routeIs('depositaire.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>Mon Profil</span>
    </a>
</div>
</div>
