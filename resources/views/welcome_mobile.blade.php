<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAKIIN - Teacher Payment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            opacity: 0.9;
            z-index: -1;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 2rem 1rem;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            line-height: 1.6;
        }

        .btn-hero {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin: 0.5rem;
            font-weight: 500;
        }

        .btn-primary-hero {
            background: linear-gradient(45deg, #fff, #f093fb);
            color: var(--dark-color);
            border: none;
        }

        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            color: var(--dark-color);
        }

        .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline-hero:hover {
            background: white;
            color: var(--dark-color);
            transform: translateY(-2px);
        }

        .features-section {
            padding: 5rem 0;
            background: white;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 2rem;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 3rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }

        .shape:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 120px; height: 120px; left: 20%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 60px; height: 60px; left: 70%; animation-delay: 4s; }
        .shape:nth-child(4) { width: 100px; height: 100px; left: 80%; animation-delay: 6s; }
        .shape:nth-child(5) { width: 140px; height: 140px; left: 90%; animation-delay: 8s; }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .btn-hero {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
                display: block;
                margin: 0.5rem auto;
                max-width: 250px;
            }

            .hero-content {
                padding: 1rem;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .feature-icon {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .btn-hero {
                padding: 0.7rem 1.2rem;
                font-size: 0.9rem;
            }

            .features-section {
                padding: 3rem 0;
            }
        }

        /* Loading animation */
        .loading {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading:nth-child(1) { animation-delay: 0.1s; }
        .loading:nth-child(2) { animation-delay: 0.2s; }
        .loading:nth-child(3) { animation-delay: 0.3s; }
        .loading:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="loading">
                            <i class="fas fa-graduation-cap fa-4x mb-4" style="color: white;"></i>
                        </div>
                        <h1 class="hero-title loading">YAKIIN</h1>
                        <p class="hero-subtitle loading">Teacher Payment System</p>
                        <p class="hero-description loading">
                            Sistem manajemen pembayaran guru yang modern, efisien, dan mudah digunakan.
                            Kelola data guru, absensi, dan penggajian dengan satu platform terintegrasi.
                        </p>
                        <div class="loading">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-hero btn-primary-hero">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-hero btn-primary-hero">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-hero btn-outline-hero">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-4 fw-bold text-dark mb-3">Fitur Unggulan</h2>
                    <p class="lead text-muted">Solusi lengkap untuk manajemen guru dan pembayaran</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="feature-title">Manajemen Guru</h4>
                        <p class="feature-description">
                            Kelola data guru, profil, dan informasi lengkap dengan mudah dan terorganisir.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h4 class="feature-title">Absensi Digital</h4>
                        <p class="feature-description">
                            Sistem absensi dengan foto selfie untuk memastikan kehadiran guru yang akurat.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4 class="feature-title">Sistem Gaji</h4>
                        <p class="feature-description">
                            Kalkulasi gaji otomatis berdasarkan kehadiran, tunjangan, dan potongan.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4 class="feature-title">Manajemen Cuti</h4>
                        <p class="feature-description">
                            Ajukan dan kelola cuti guru dengan sistem persetujuan yang terintegrasi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="feature-title">Mobile Friendly</h4>
                        <p class="feature-description">
                            Responsif dan mudah digunakan di semua perangkat, termasuk smartphone dan tablet.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="feature-title">Laporan & Analisis</h4>
                        <p class="feature-description">
                            Dashboard dengan statistik dan laporan lengkap untuk monitoring kinerja.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-title">Keamanan Data</h4>
                        <p class="feature-description">
                            Data guru dan transaksi tersimpan aman dengan enkripsi dan backup otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} YAKIIN Teacher Payment System. All rights reserved.</p>
                    <p class="text-muted small">Sistem Manajemen Pembayaran Guru Modern</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const loadingElements = document.querySelectorAll('.loading');
            loadingElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add haptic feedback on mobile
        if ('vibrate' in navigator) {
            document.querySelectorAll('.btn-hero').forEach(button => {
                button.addEventListener('click', function() {
                    navigator.vibrate(50);
                });
            });
        }
    </script>
</body>
</html>
