<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum - Inventory System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <script>
        // Inline script to prevent theme flash
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.15);
            --danger: #f43f5e;
            --danger-glow: rgba(244, 63, 94, 0.15);
            --warning: #f59e0b;
            --warning-glow: rgba(245, 158, 11, 0.15);

            --sidebar-width: 260px;
        }

        /* Light Theme Overrides */
        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.85);
            --border-color: rgba(15, 23, 42, 0.08);
            
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.1);
            --success: #059669;
            --success-glow: rgba(5, 150, 105, 0.1);
            --danger: #e11d48;
            --danger-glow: rgba(225, 29, 72, 0.1);
            --warning: #d97706;
            --warning-glow: rgba(217, 119, 6, 0.1);
        }

        /* Light mode text colors overrides */
        [data-theme="light"] .text-light {
            color: var(--text-primary) !important;
        }
        [data-theme="light"] .text-white {
            color: var(--text-primary) !important;
        }
        [data-theme="light"] .btn-close-white {
            filter: invert(1) !important;
        }
        [data-theme="light"] .modal-content {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }
        [data-theme="light"] .table-custom tr {
            background-color: rgba(0, 0, 0, 0.02) !important;
        }
        [data-theme="light"] .table-custom tr:hover {
            background-color: rgba(0, 0, 0, 0.04) !important;
        }
        [data-theme="light"] .glass-card {
            background: var(--bg-card);
            box-shadow: 0 8px 32px 0 rgba(15, 23, 42, 0.08);
        }
        [data-theme="light"] .glass-card:hover {
            box-shadow: 0 12px 40px 0 rgba(15, 23, 42, 0.12);
        }
        [data-theme="light"] .bg-secondary-glow {
            background: rgba(15, 23, 42, 0.05) !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Responsive Sidebar Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .main-content {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Glassmorphism card */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
        }

        /* Navigation Links */
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .brand-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-link-custom {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--text-primary);
            background: var(--primary-glow);
            border-left: 3px solid var(--primary);
        }

        /* Top Bar */
        .top-bar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        /* Form Controls */
        .form-control-custom {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: var(--text-primary) !important;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            outline: none;
        }

        /* Select styling override */
        select.form-control-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
            border: none;
            color: white !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Badges */
        .badge-soft-danger {
            background: var(--danger-glow);
            color: var(--danger);
            border: 1px solid rgba(244, 63, 94, 0.2);
            font-weight: 600;
        }

        .badge-soft-success {
            background: var(--success-glow);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
            font-weight: 600;
        }

        .badge-soft-primary {
            background: var(--primary-glow);
            color: var(--primary);
            border: 1px solid rgba(99, 102, 241, 0.2);
            font-weight: 600;
        }

        .badge-soft-warning {
            background: var(--warning-glow);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
            font-weight: 600;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Table custom styling */
        .table-custom {
            color: var(--text-primary) !important;
            border-collapse: separate;
            border-spacing: 0 8px;
            background-color: transparent !important;
        }

        .table-custom th {
            color: var(--text-secondary) !important;
            font-weight: 600;
            border: none;
            padding: 1rem;
            background-color: transparent !important;
        }

        .table-custom tr {
            background-color: rgba(255, 255, 255, 0.03) !important;
            transition: all 0.2s ease;
        }

        .table-custom tr:hover {
            background-color: rgba(255, 255, 255, 0.06) !important;
            transform: scale(1.002);
        }

        .table-custom td {
            color: var(--text-primary) !important;
            border: none;
            padding: 1.25rem 1rem;
            vertical-align: middle;
            background-color: transparent !important;
        }

        .table-custom tr td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table-custom tr td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Sidebar Toggle Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1020;
        }

        /* Micro-interactions */
        .hover-scale {
            transition: transform 0.2s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar.show + .sidebar-overlay {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <div class="app-container">
        @auth
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand d-flex align-items-center justify-content-between">
                <a class="d-flex align-items-center text-decoration-none" href="{{ route('dashboard') }}">
                    <i class="bi bi-hexagon-fill text-primary me-2 fs-4"></i>
                    <span class="brand-text fs-3">QUANTUM</span>
                </a>
                <button class="btn btn-close btn-close-white d-lg-none" onclick="toggleSidebar()"></button>
            </div>
            
            <nav class="sidebar-nav">
                <a class="nav-link-custom {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a class="nav-link-custom {{ Request::routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Products Inventory</span>
                </a>

                @if(Auth::user()->hasAnyRole(['staff']))
                    <a class="nav-link-custom {{ Request::routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.create') }}">
                        <i class="bi bi-cart-dash"></i>
                        <span>Sale Stock</span>
                    </a>
                @endif

                <a class="nav-link-custom {{ Request::routeIs('reports.stock-out') ? 'active' : '' }}" href="{{ route('reports.stock-out') }}">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Stock Out Report</span>
                </a>
            </nav>

            <div class="p-3 border-top border-secondary">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary-custom w-100 py-2">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
        @endauth

        <!-- Main Content Wrapper -->
        <div class="main-content">
            <!-- Top Header Bar -->
            <header class="top-bar">
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <button class="btn btn-secondary-custom d-lg-none py-1.5 px-2.5" onclick="toggleSidebar()">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    @endauth
                    
                    @guest
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hexagon-fill text-primary me-2 fs-4"></i>
                            <span class="brand-text fs-3">QUANTUM</span>
                        </div>
                    @endguest
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Light/Dark Mode Toggle Switch -->
                    <button class="btn btn-secondary-custom py-2 px-3" onclick="toggleTheme()" id="themeToggleBtn" title="Toggle Light/Dark Mode">
                        <i class="bi bi-sun" id="themeIcon"></i>
                    </button>

                    @auth
                        <div class="text-end d-none d-md-block">
                            <div class="fw-semibold small text-light">{{ Auth::user()->name }}</div>
                            <div style="font-size: 11px;">
                                <span class="badge badge-soft-primary px-2 py-0.5">
                                    {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                                </span>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Content Area -->
            <main class="container-fluid py-5 px-4 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show glass-card border-success text-success mb-4 p-3 d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show glass-card border-danger text-danger mb-4 p-3" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <strong class="fw-semibold">Action Required:</strong>
                        </div>
                        <ul class="mb-0 ps-4 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                if (theme === 'light') {
                    icon.className = 'bi bi-moon-stars';
                } else {
                    icon.className = 'bi bi-sun';
                }
            }
        }

        // Initialize theme icon on load
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            updateThemeIcon(currentTheme);
        });
    </script>
</body>
</html>
