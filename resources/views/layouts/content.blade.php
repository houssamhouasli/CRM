<div class="page-content">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-custom alert-success-custom animate-in mb-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-custom alert-error-custom animate-in mb-3">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-custom alert-error-custom animate-in mb-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
