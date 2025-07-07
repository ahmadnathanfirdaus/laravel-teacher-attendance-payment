<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YAKIIN - Teacher Payment System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }
        .sidebar .collapse .nav-link {
            font-size: 0.9rem;
            padding-left: 2rem;
        }
        .main-content {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }
        /* Custom scrollbar for sidebar */
        .sidebar {
            overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #495057;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3 position-fixed" style="width: 250px; z-index: 1000;">
            <div class="text-center mb-4">
                <h4 class="text-white">YAKIIN</h4>
                <small class="text-muted">Teacher Payment System</small>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>

                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'bendahara')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teachers.index') }}">
                        <i class="fas fa-users me-2"></i>
                        Data Guru
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin')
                <!-- Admin Only Menu -->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#managementMenu" aria-expanded="false">
                        <i class="fas fa-cogs me-2"></i>
                        Manajemen
                        <i class="fas fa-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="managementMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('shifts.index') }}">
                                    <i class="fas fa-clock me-2"></i>
                                    Shift Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('positions.index') }}">
                                    <i class="fas fa-briefcase me-2"></i>
                                    Jabatan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('allowance-types.index') }}">
                                    <i class="fas fa-tags me-2"></i>
                                    Jenis Tunjangan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher-allowances.index') }}">
                                    <i class="fas fa-hand-holding-usd me-2"></i>
                                    Tunjangan Guru
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('education-levels.index') }}">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Jenjang Pendidikan
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->user()->role === 'guru')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('self-attendance.index') }}">
                        <i class="fas fa-camera me-2"></i>
                        Absensi Mandiri
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendances.index') }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        Absensi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('salaries.index') }}">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Gaji
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main content -->
        <div class="main-content flex-grow-1">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="d-flex align-items-center ms-auto">
                        <span class="me-3">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                        </span>
                    </div>
                </div>
            </nav>

            <!-- Page content -->
            <main class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
