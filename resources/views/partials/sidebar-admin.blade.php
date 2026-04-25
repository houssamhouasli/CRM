{{-- Admin General Sidebar --}}
<div class="nav-section">
    <div class="nav-section-title">Principal</div>
    <a href="{{ route('admin.dashboard') }}"
        class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Gestion</div>
    <a href="{{ route('admin.regions.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt-fill"></i>
        <span>Régions</span>
    </a>
    <a href="{{ route('admin.users.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i>
        <span>Utilisateurs</span>
    </a>
    <a href="{{ route('admin.clients.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i>
        <span>Clients</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Catalogue</div>
    <a href="{{ route('admin.products.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i>
        <span>Produits</span>
    </a>
    <a href="{{ route('admin.categories.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i>
        <span>Catégories</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Commandes & Stock</div>
    <a href="{{ route('admin.orders.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="bi bi-cart-fill"></i>
        <span>Commandes</span>
    </a>
    <a href="{{ route('admin.deliveries.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">
        <i class="bi bi-truck"></i>
        <span>Livraisons</span>
    </a>
    <a href="{{ route('admin.returns.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-return-left"></i>
        <span>Retours</span>
        @php
            $adminPendingReturns = \App\Models\ReturnModel::where('status', 'pending')->count();
        @endphp
        @if($adminPendingReturns > 0)
        <span class="badge bg-danger ms-auto" style="font-size: 0.6rem;">{{ $adminPendingReturns }}</span>
        @endif
    </a>
    <a href="{{ route('admin.stock.index') }}"
        class="nav-item-link {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right"></i>
        <span>Mouvements Stock</span>
    </a>
</div>

<div class="nav-section mt-auto mb-3">
    <div class="nav-section-title">Paramètres</div>
    <a href="{{ route('admin.profile') }}"
        class="nav-item-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>Mon Profil</span>
    </a>
</div>