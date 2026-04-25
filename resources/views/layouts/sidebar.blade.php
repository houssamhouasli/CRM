<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <img src="{{ asset('favicon.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
        </div>
        <div>
            <div class="brand-text text-white">Lesaffre Maroc</div>
            <div class="brand-sub">CRM & Gestion de Stock</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar')
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name text-white">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">
                    @if(auth()->user()->isAdmin()) Admin
                    @elseif(auth()->user()->isCommercial()) Commercial
                    @elseif(auth()->user()->isDepositaire()) Dépositaire
                    @elseif(auth()->user()->isLivreur()) Livreur
                    @else Client
                    @endif
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-sm" style="color: var(--text-secondary);" title="Déconnexion">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
