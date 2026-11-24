<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Form &amp; Field</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --forest: #435344;
            --forest-dark: #334235;
            --sage: #78866f;
            --cream: #f5f4ef;
            --ink: #292d28;
            --muted: #81857e;
            --line: #e9e9e2;
        }
        body { background: var(--cream); color: var(--ink); font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .admin-sidebar { background: var(--forest); color: #fff; min-height: 100vh; width: 252px; flex: 0 0 252px; }
        .brand-mark { width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; background: #f7f6f0; color: var(--forest); font: 700 22px Georgia, serif; }
        .brand-name { font: 600 19px Georgia, serif; letter-spacing: -.4px; }
        .sidebar-caption { color: #b7c0b3; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; }
        .sidebar-link { color: #e1e5de; text-decoration: none; display: flex; align-items: center; gap: 12px; border-radius: 8px; padding: 11px 13px; font-size: 14px; }
        .sidebar-link.active, .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.13); }
        .sidebar-link i { font-size: 17px; width: 20px; }
        .admin-main { min-width: 0; flex: 1; }
        .topbar { background: #fff; border-bottom: 1px solid var(--line); min-height: 76px; }
        .eyebrow { color: var(--sage); font-size: 11px; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; }
        .page-title { font: 500 32px Georgia, "Times New Roman", serif; letter-spacing: -.6px; }
        .card-clean { border: 1px solid var(--line); border-radius: 12px; background: #fff; box-shadow: 0 3px 14px rgba(37, 43, 35, .025); }
        .metric-label { color: var(--muted); font-size: 13px; }
        .metric-value { font: 500 30px Georgia, "Times New Roman", serif; letter-spacing: -.5px; }
        .metric-icon { width: 42px; height: 42px; border-radius: 10px; display: grid; place-items: center; background: #edf0e9; color: var(--forest); font-size: 19px; }
        .table thead th { color: var(--muted); font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; border-bottom-color: var(--line); }
        .table td { border-bottom-color: #f0f0eb; padding-top: 15px; padding-bottom: 15px; }
        .status-pill { display: inline-block; border-radius: 20px; padding: 5px 10px; font-size: 11px; text-transform: capitalize; background: #f0f0eb; color: #62675f; }
        .status-delivered { background: #e9f2e8; color: #476648; }
        .status-processing, .status-shipped { background: #edf1f5; color: #4a6179; }
        .status-cancelled { background: #f9eaea; color: #9a4c4c; }
        .status-pending { background: #fbf2df; color: #8d6a2c; }
        .text-muted-custom { color: var(--muted); }
        .btn-forest { background: var(--forest); border-color: var(--forest); color: #fff; }
        .btn-forest:hover { background: var(--forest-dark); border-color: var(--forest-dark); color: #fff; }
        .empty-icon { width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center; background: var(--cream); color: var(--sage); font-size: 20px; }
        @media (max-width: 767.98px) {
            .admin-sidebar { width: 70px; flex-basis: 70px; }
            .brand-name, .sidebar-caption, .sidebar-link span, .sidebar-footer { display: none; }
            .admin-sidebar .brand-mark { margin-inline: auto; }
            .sidebar-link { justify-content: center; padding-inline: 8px; }
            .sidebar-link i { width: auto; }
            .page-title { font-size: 27px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex min-vh-100">
    <aside class="admin-sidebar d-flex flex-column px-3 py-4">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white mb-5 px-1">
            <span class="brand-mark">f.</span>
            <span class="brand-name">form &amp; field</span>
        </a>
        <div class="sidebar-caption px-2 mb-2">Store management</div>
        <nav class="d-grid gap-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i><span>Overview</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i><span>Products</span>
            </a>
        </nav>
        <div class="mt-auto sidebar-footer border-top border-light border-opacity-25 pt-3">
            <div class="small fw-semibold">{{ auth()->user()->name }}</div>
            <div class="small text-white-50">Administrator</div>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar d-flex justify-content-between align-items-center px-4 px-lg-5">
            <div class="small text-muted-custom d-none d-sm-block">Thoughtful goods, made for everyday living</div>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a class="small text-decoration-none text-muted-custom" href="http://16.176.28.191/" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right me-1"></i> View store
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary" type="submit">Sign out</button>
                </form>
            </div>
        </header>
        <div class="container-fluid px-4 px-lg-5 py-4 py-lg-5">
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
