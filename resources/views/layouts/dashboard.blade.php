<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <title>@yield('title', 'SmileCare Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-scroll {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 12px 10px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            background: linear-gradient(
                180deg,
                rgba(0, 0, 0, 0) 0%,
                rgba(0, 0, 0, 0.12) 100%
            );
        }

        .sidebar-footer .nav-link,
        .sidebar-footer button.nav-link {
            margin: 0 !important;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(5px);
        }

        /*
        |--------------------------------------------------------------------------
        | Temporarily Disabled Admin Modules
        |--------------------------------------------------------------------------
        */

        .sidebar .nav-link.disabled-module {
            color: rgba(255, 255, 255, 0.45) !important;
            background: transparent !important;
            cursor: not-allowed !important;
            pointer-events: none;
            transform: none !important;
            opacity: 0.65;
        }

        .sidebar .nav-link.disabled-module i {
            color: rgba(255, 255, 255, 0.45) !important;
        }

        .sidebar .nav-link.disabled-module .module-badge {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 9px;
            line-height: 1;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.65);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .page-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: none;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #2c3e50;
        }

        .brand-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .brand-logo {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .user-info {
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            margin: 10px;
            border-radius: 10px;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar">

        <div class="sidebar-scroll">

            <!-- Brand -->
            <div class="brand-header">
                <div class="brand-logo">
                    <i class="fas fa-tooth"></i>
                </div>

                <h4 class="mb-0">Smile Care Ltd</h4>

                <small class="opacity-75">
                    Case Management System
                </small>
            </div>

            <!-- User Information -->
            <div class="user-info">
                <div class="d-flex align-items-center">

                    <i class="fas fa-user-circle fa-2x me-3"></i>

                    <div>
                        <div class="fw-bold">
                            {{ Auth::user()->name }}
                        </div>

                        <small class="opacity-75">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </small>
                    </div>

                </div>
            </div>

            <!-- Navigation -->
            <ul class="nav flex-column mb-0">

                @if(Auth::user()->isAdmin())

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}"
                        >
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Marketing Representatives -->
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.mrs*') ? 'active' : '' }}"
                            href="{{ route('admin.mrs.index') }}"
                        >
                            <i class="fas fa-user-tie me-2"></i>
                            Marketing Representatives
                        </a>
                    </li>

                    <!-- Doctors -->
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}"
                            href="{{ route('admin.doctors.index') }}"
                        >
                            <i class="fas fa-user-doctor me-2"></i>
                            Doctors
                        </a>
                    </li>

                    <!-- Patients -->
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}"
                            href="{{ route('admin.patients.index') }}"
                        >
                            <i class="fas fa-users me-2"></i>
                            Patients
                        </a>
                    </li>

                    <!-- =================================================
                         DISABLED: LAB TECHNICIANS
                         ================================================= -->
                    <li class="nav-item">

                        <span
                            class="nav-link disabled-module d-flex align-items-center"
                            aria-disabled="true"
                            title="Lab Technicians module is temporarily unavailable"
                        >

                            <i class="fas fa-user-md me-2"></i>

                            <span>
                                Lab Technicians
                            </span>

                            <span class="module-badge">
                                Soon
                            </span>

                        </span>

                    </li>

                    <!-- Case Management -->
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}"
                            href="{{ route('admin.payments.index') }}"
                        >
                            <i class="fas fa-credit-card me-2"></i>
                            Case Management
                        </a>
                    </li>

                    <!-- =================================================
                         DISABLED: INVENTORY
                         ================================================= -->
                    <li class="nav-item">

                        <span
                            class="nav-link disabled-module d-flex align-items-center"
                            aria-disabled="true"
                            title="Inventory module is temporarily unavailable"
                        >

                            <i class="fas fa-boxes me-2"></i>

                            <span>
                                Inventory
                            </span>

                            <span class="module-badge">
                                Soon
                            </span>

                        </span>

                    </li>

                    <!-- =================================================
                         DISABLED: PRODUCTION FIELD
                         ================================================= -->
                    <li class="nav-item">

                        <span
                            class="nav-link disabled-module d-flex align-items-center"
                            aria-disabled="true"
                            title="Production Field module is temporarily unavailable"
                        >

                            <i class="fas fa-industry me-2"></i>

                            <span>
                                Production Field
                            </span>

                            <span class="module-badge">
                                Soon
                            </span>

                        </span>

                    </li>

                    <!-- Reports -->
                    <li class="nav-item">

                        <a
                            href="{{ route('admin.reports.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                        >

                            <i class="fas fa-chart-bar me-2"></i>

                            <span>
                                Reports
                            </span>

                        </a>

                    </li>

                @else

                    <!-- =================================================
                         LAB TECHNICIAN NAVIGATION
                         ================================================= -->

                    <!-- Dashboard -->
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('lab.dashboard') ? 'active' : '' }}"
                            href="{{ route('lab.dashboard') }}"
                        >

                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard

                        </a>

                    </li>

                    <!-- Patients -->
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('lab.patients*') ? 'active' : '' }}"
                            href="{{ route('lab.patients.index') }}"
                        >

                            <i class="fas fa-users me-2"></i>
                            View Patients

                        </a>

                    </li>

                    <!-- Inventory -->
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('lab.inventory*') ? 'active' : '' }}"
                            href="{{ route('lab.inventory.index') }}"
                        >

                            <i class="fas fa-boxes me-2"></i>
                            Check Inventory

                        </a>

                    </li>

                    @php
                        $labProductsMenuOpen =
                            request()->routeIs('lab.products*') ||
                            request()->routeIs('lab.cart.index') ||
                            request()->routeIs('lab.cart.confirmed');
                    @endphp

                    <!-- Products -->
                    <li class="nav-item">

                        <a
                            class="nav-link d-flex justify-content-between align-items-center {{ $labProductsMenuOpen ? 'active' : '' }}"
                            data-bs-toggle="collapse"
                            href="#labProductsMenu"
                            role="button"
                            aria-expanded="{{ $labProductsMenuOpen ? 'true' : 'false' }}"
                            aria-controls="labProductsMenu"
                        >

                            <span>
                                <i class="fas fa-shopping-bag me-2"></i>
                                Products
                            </span>

                            <i class="fas fa-chevron-down small"></i>

                        </a>

                        <div
                            class="collapse {{ $labProductsMenuOpen ? 'show' : '' }}"
                            id="labProductsMenu"
                        >

                            <ul class="nav flex-column ms-3 my-2">

                                <!-- Browse Products -->
                                <li class="nav-item">

                                    <a
                                        class="nav-link {{ request()->routeIs('lab.products*') ? 'active' : '' }}"
                                        href="{{ route('lab.products.index') }}"
                                    >

                                        <i class="fas fa-boxes me-2"></i>
                                        Browse Products

                                    </a>

                                </li>

                                <!-- My Cart -->
                                <li class="nav-item">

                                    <a
                                        class="nav-link {{ request()->routeIs('lab.cart.index') ? 'active' : '' }}"
                                        href="{{ route('lab.cart.index') }}"
                                    >

                                        <i class="fas fa-shopping-cart me-2"></i>
                                        My Cart

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <!-- Production Field -->
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('lab.production*') ? 'active' : '' }}"
                            href="{{ route('lab.production.index') }}"
                        >

                            <i class="fas fa-industry me-2"></i>
                            Production Field

                        </a>

                    </li>

                @endif

            </ul>

        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mb-0"
            >

                @csrf

                <button
                    type="submit"
                    class="nav-link border-0 bg-transparent text-start w-100 text-white py-2"
                >

                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout

                </button>

            </form>

        </div>

    </nav>


    <!-- Main Content -->
    <main class="main-content">

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="fas fa-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="fas fa-exclamation-triangle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @yield('content')

    </main>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page-specific scripts -->
    @stack('scripts')

</body>
</html>