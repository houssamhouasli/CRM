<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <p>@yield('page-subtitle', '')</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-4">
        @auth
            @if(auth()->user()->role === 'depositaire')
                @php
                    $depotId = auth()->user()->depot_id;
                    $allProducts = \App\Models\Product::with('category')->get();
                    $lowStockProducts = collect();
                    foreach($allProducts as $product) {
                        $totalStock = \App\Models\DepotStock::where('product_id', $product->id)
                            ->where('depot_id', $depotId)
                            ->sum('quantity');
                        
                        if($totalStock < 50) {
                            $product->current_total_stock = $totalStock;
                            $lowStockProducts->push($product);
                        }
                    }
                    $lowStockCount = $lowStockProducts->count();
                    $displayLowStock = $lowStockProducts->take(5);
                @endphp
                
                @if($lowStockCount > 0)
                <div class="dropdown" style="margin-right: 15px;">
                    <a href="#" class="text-decoration-none position-relative animate-in dropdown-toggle text-secondary" id="stockDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="display: inline-block;">
                        <i class="bi bi-bell-fill fs-4"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                            {{ $lowStockCount }}
                        </span>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end p-0 shadow overflow-hidden border-0" aria-labelledby="stockDropdown" style="width: 350px; border-radius: 0.75rem;">
                        <div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Alerte Stock Dépôt</h6>
                            <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill">{{ $lowStockCount }} alerts</span>
                        </div>
                        <div class="p-0" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <tbody>
                                    @foreach($displayLowStock as $product)
                                    <tr>
                                        <td class="py-2 px-3 border-bottom">
                                            <span class="d-block fw-bold text-dark">{{ $product->name }}</span>
                                            <span class="text-muted" style="font-size: 0.75rem;">{{ $product->category->name ?? '—' }}</span>
                                        </td>
                                        <td class="text-end py-2 px-3 border-bottom w-25">
                                            @if($product->current_total_stock <= 0)
                                                <span class="badge bg-danger">Rupture</span>
                                            @else
                                                <span class="badge bg-warning text-dark border border-warning">{{ $product->current_total_stock }} {{ $product->unit ?? 'u' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-light p-2 text-center border-top">
                            <a href="{{ route('depositaire.stock.index') }}" class="btn btn-sm btn-outline-danger w-100 fw-bold">Gérer le stock du dépôt</a>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @endauth
        
        @yield('topbar-actions') 
    </div>
</div>
