<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin WebGIS Kriminalitas')</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #ff4d6d;
            --primary-hover: #e03a58;
            --sidebar-bg: #111827;
            --main-bg: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--main-bg);
            color: #334155;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 4px 0 24px rgba(0,0,0,0.04);
            z-index: 10;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 0.5rem;
            margin-bottom: 2rem;
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), #ff7a8a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 4px 14px rgba(255, 77, 109, 0.4);
        }

        .sidebar-brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.3px;
            color: #ffffff;
        }

        /* Nav Group & Links */
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
            letter-spacing: 0.8px;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 6px 18px rgba(255, 77, 109, 0.35);
        }

        .sidebar .nav-link.active i {
            color: #ffffff;
        }

        /* Main Area dengan Corak Halus */
        .main-content {
            background-color: var(--main-bg);
            background-image: radial-gradient(#cbd5e1 0.8px, transparent 0.8px);
            background-size: 20px 20px;
            min-height: 100vh;
            padding: 2rem !important;
        }

        /* Header Bar Atas */
        .top-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 0.85rem 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .top-navbar-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #0f172a;
            margin: 0;
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 0.35rem 0.9rem 0.35rem 0.4rem;
            border-radius: 30px;
        }

        .admin-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        /* Alert Styling */
        .custom-alert {
            border: none;
            border-radius: 12px;
            padding: 0.9rem 1.2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .sidebar { min-height: auto; }
            .main-content { padding: 1.25rem !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div>
                <!-- Brand Icon & Title -->
                <a href="#" class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <span class="sidebar-brand-text">WebGIS Crime</span>
                </a>

                <!-- Nav Menu Group -->
                <div class="nav-label">Menu Utama</div>
                <ul class="nav flex-column gap-1 mb-4">
                    <li class="nav-item">
                        <a href="{{ route('admin.kriminalitas.index') }}" class="nav-link {{ request()->routeIs('admin.kriminalitas*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> Data Kriminalitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kecamatan.index') }}" class="nav-link {{ request()->routeIs('admin.kecamatan*') ? 'active' : '' }}">
                            <i class="fas fa-map"></i> Data Kecamatan
                        </a>
                    </li>
                    <!-- Menu Rekap Wilayah berada di dalam tag <ul> ini -->
                    <li class="nav-item">
                        <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i> Rekap Wilayah
                        </a>
                    </li>
                </ul>

                <div class="nav-label">Akses Luar</div>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('map.index') }}" class="nav-link" target="_blank">
                            <i class="fas fa-globe"></i> Lihat Peta
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 col-lg-10 main-content">
            <!-- Header Atas / Navigation Bar -->
            <div class="top-navbar">
                <h1 class="top-navbar-title">Panel Administrator</h1>
                
                <!-- Dropdown Profil Admin (Hanya Logout) -->
                <div class="dropdown">
                    <a href="#" class="admin-badge text-decoration-none dropdown-toggle" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <div class="admin-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="fw-bold fs-7 text-dark pe-1">Admin</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="adminDropdown" style="border-radius: 12px; min-width: 180px;">
                        <li><h6 class="dropdown-header text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Sesi Aktif</h6></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <!-- Opsi Logout -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 fw-semibold" style="font-size: 0.85rem;">
                                    <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="alert alert-success custom-alert alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-check-circle fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger custom-alert alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-exclamation-triangle fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Isi Konten Utama -->
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>