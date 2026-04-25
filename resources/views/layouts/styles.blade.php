<style>
    :root {
        --primary: #0a3b8f; /* rgb(10, 59, 143) */
        --primary-dark: #072763;
        --primary-light: #205abe;
        --secondary: #0e1e3a;
        --accent: #E8841A;
        --accent-light: #f5a623;
        --success: #27ae60;
        --info: #2980b9;
        --warning: #f39c12;
        --danger: #e74c3c;
        --sidebar-width: 280px;
        --sidebar-collapsed: 70px;
        --bg-dark: #f0f2f5;
        --bg-card: #ffffff;
        --bg-card-hover: #f8f9fb;
        --text-primary: #1a1a2e;
        --text-secondary: #6b7a8d;
        --border-color: #e0e4ea;
        --glass-bg: rgba(255, 255, 255, 0.92);
        --glass-border: rgba(0, 0, 0, 0.06);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-dark);
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ── Sidebar ── */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: linear-gradient(180deg, #0e1e3a 0%, #091428 100%);
        border-right: 1px solid var(--glass-border);
        z-index: 1050;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-brand {
        padding: 1.5rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-brand .brand-icon {
        width: 42px;
        height: 42px;
        background: #ffffff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(232, 132, 26, 0.35);
    }

    .sidebar-brand .brand-text {
        font-size: 0.94rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .sidebar-brand .brand-sub {
        font-size: 0.7rem;
        color: var(--accent-light);
        font-weight: 400;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1rem 0.75rem;
    }

    .nav-section {
        margin-bottom: 1.5rem;
    }

    .nav-section-title {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-secondary);
        padding: 0 0.75rem;
        margin-bottom: 0.5rem;
    }

    .nav-item-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0.75rem;
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        position: relative;
    }

    .nav-item-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .nav-item-link.active {
        background: linear-gradient(135deg, rgba(232, 132, 26, 0.2), rgba(255,255,255, 0.05));
        color: var(--accent-light);
        font-weight: 600;
    }

    .nav-item-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background: var(--accent);
        border-radius: 0 3px 3px 0;
    }

    .nav-item-link i {
        font-size: 1.1rem;
        width: 22px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--glass-border);
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #fff;
        flex-shrink: 0;
    }

    .sidebar-user-info {
        flex: 1;
        min-width: 0;
    }

    .sidebar-user-name {
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-user-role {
        font-size: 0.7rem;
        color: var(--text-secondary);
    }

    /* ── Main Content ── */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar {
        padding: 1rem 2rem;
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .topbar-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .topbar-title p {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .page-content {
        padding: 1.5rem 2rem;
    }

    /* ── Cards ── */
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
    }

    .stat-card.red::before { background: linear-gradient(90deg, #c8102e, #ff4d4d); }
    .stat-card.blue::before { background: linear-gradient(90deg, var(--info), #5dade2); }
    .stat-card.green::before { background: linear-gradient(90deg, var(--success), #2ecc71); }
    .stat-card.orange::before { background: linear-gradient(90deg, var(--accent), var(--accent-light)); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8e44ad, #9b59b6); }
    .stat-card.primary::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 48, 135, 0.15);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .stat-icon.red { background: rgba(200, 16, 46, 0.1); color: #c8102e; }
    .stat-icon.blue { background: rgba(41, 128, 185, 0.15); color: #5dade2; }
    .stat-icon.green { background: rgba(39, 174, 96, 0.15); color: #2ecc71; }
    .stat-icon.orange { background: rgba(232, 132, 26, 0.1); color: var(--accent); }
    .stat-icon.purple { background: rgba(142, 68, 173, 0.15); color: #9b59b6; }
    .stat-icon.primary { background: rgba(10, 59, 143, 0.1); color: var(--primary); }

    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        margin-top: 0.75rem;
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* ── Data Card / Table Card ── */
    .data-card {
        background: var(--bg-card);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .data-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--glass-border);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .data-card .card-header h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .data-card .card-body {
        padding: 1.5rem;
    }

    /* ── Tables ── */
    .table-dark-custom {
        --bs-table-bg: transparent;
        --bs-table-color: var(--text-primary);
        --bs-table-border-color: var(--border-color);
        --bs-table-hover-bg: var(--bg-card-hover);
        --bs-table-hover-color: var(--text-primary);
        --bs-table-striped-bg: rgba(255,255,255,0.02);
        margin-bottom: 0;
    }

    .table-dark-custom thead th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        padding: 0.85rem 1rem;
    }

    .table-dark-custom tbody td {
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* ── Badges ── */
    .badge-status {
        padding: 0.4em 0.85em;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pending {
        background: rgba(232, 132, 26, 0.1);
        color: #c7700e;
    }

    .badge-validated, .badge-confirmed {
        background: rgba(10, 59, 143, 0.1);
        color: var(--primary);
    }

    .badge-delivered, .badge-completed, .badge-livrer {
        background: rgba(39, 174, 96, 0.12);
        color: #1e8449;
    }

    .badge-partial {
        background: rgba(41, 128, 185, 0.12);
        color: #2980b9;
    }

    .badge-canceled, .badge-cancelled, .badge-annuler, .badge-annulee {
        background: rgba(200, 16, 46, 0.1);
        color: #c8102e;
    }

    .badge-validated, .badge-confirmed, .badge-valide, .badge-validee {
        background: rgba(10, 59, 143, 0.1);
        color: var(--primary);
    }

    /* ── Forms ── */
    .form-control, .form-select {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        background: var(--bg-card-hover);
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 48, 135, 0.1);
        color: var(--text-primary);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.4rem;
    }

    /* ── Buttons ── */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        color: #fff;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 48, 135, 0.25);
        color: #fff;
    }

    .btn-outline-custom {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-outline-custom:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(0, 48, 135, 0.04);
    }

    /* ── Alerts ── */
    .alert-custom {
        border-radius: 12px;
        border: 1px solid;
        padding: 1rem 1.25rem;
        font-size: 0.875rem;
    }

    .alert-success-custom {
        background: rgba(39, 174, 96, 0.1);
        border-color: rgba(39, 174, 96, 0.3);
        color: #2ecc71;
    }

    .alert-error-custom {
        background: rgba(231, 76, 60, 0.1);
        border-color: rgba(231, 76, 60, 0.3);
        color: #e74c3c;
    }

    /* ── Pagination Dark ── */
    .pagination .page-link {
        background: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-secondary);
        border-radius: 8px !important;
        margin: 0 2px;
        font-size: 0.85rem;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .pagination .page-link:hover {
        background: var(--bg-card-hover);
        color: var(--text-primary);
    }

    /* ── Mobile Toggle ── */
    .sidebar-toggle {
        display: none;
        background: var(--bg-card);
        border: 1px solid var(--glass-border);
        color: var(--text-primary);
        padding: 0.5rem;
        border-radius: 10px;
        font-size: 1.25rem;
        cursor: pointer;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 1045;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar-overlay.show {
            display: block;
        }
        .main-content {
            margin-left: 0;
        }
        .sidebar-toggle {
            display: inline-flex;
        }
        .page-content {
            padding: 1rem;
        }
        .topbar {
            padding: 0.75rem 1rem;
        }
    }

    /* ── Animations ── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-in {
        animation: fadeInUp 0.5s ease forwards;
    }

    .animate-in:nth-child(2) { animation-delay: 0.05s; }
    .animate-in:nth-child(3) { animation-delay: 0.10s; }
    .animate-in:nth-child(4) { animation-delay: 0.15s; }
    .animate-in:nth-child(5) { animation-delay: 0.20s; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #c8cdd5; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #a0a8b4; }

    /* ── Modal Dark ── */
    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        color: var(--text-primary);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-color);
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
    }

    .btn-close {
        filter: none;
    }
</style>
