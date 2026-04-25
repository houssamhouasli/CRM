{{-- Admin Regional Sidebar --}}
<div class="nav-section">
    <div class="nav-section-title">Principal</div>
    <a href="{{ route('commercial.dashboard') }}"
        class="nav-item-link {{ request()->routeIs('commercial.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Ma Région</div>
    <a href="{{ route('commercial.clients.index') }}"
        class="nav-item-link {{ request()->routeIs('commercial.clients.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i>
        <span>Clients</span>
    </a>
    <a href="{{ route('commercial.orders.index') }}"
        class="nav-item-link {{ request()->routeIs('commercial.orders.*') ? 'active' : '' }}">
        <i class="bi bi-cart-fill"></i>
        <span>Commandes</span>
    </a>
    <a href="{{ route('commercial.deliveries.index') }}"
        class="nav-item-link {{ request()->routeIs('commercial.deliveries.*') ? 'active' : '' }}">
        <i class="bi bi-truck"></i>
        <span>Livraisons</span>
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Catalogue</div>
    <a href="{{ route('commercial.products.index') }}"
        class="nav-item-link {{ request()->routeIs('commercial.products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i>
        <span>Produits</span>
    </a>
</div>

<div class="nav-section mt-auto mb-3">
    <div class="nav-section-title">Paramètres</div>
    <a href="{{ route('commercial.profile') }}"
        class="nav-item-link {{ request()->routeIs('commercial.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>Mon Profil</span>
    </a>
</div>