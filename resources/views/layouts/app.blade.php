<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dokumentasi Kegiatan BPBD DIY') - BPBD DIY</title>

    <!-- Google Fonts: Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS Dokumen Formal BPBD DIY -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Navbar Resmi BPBD DIY (no-print) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-bpbd no-print">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('kegiatan.index') }}">
                <i class="bi bi-shield-shaded fs-4 text-warning"></i>
                <span>BPBD DAERAH ISTIMEWA YOGYAKARTA</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kegiatan.index') ? 'active fw-bold' : '' }}" href="{{ route('kegiatan.index') }}">
                            <i class="bi bi-table me-1"></i> Data Kegiatan
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-warning btn-sm fw-semibold text-dark" href="{{ route('kegiatan.create') }}">
                            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kegiatan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-4">
        <!-- Flash Alert Messages (no-print) -->
        <div class="no-print">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <strong class="fw-bold">Terjadi kesalahan pengisian form:</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Dynamic View Content -->
        @yield('content')
    </main>

    <!-- Lightbox Modal untuk Preview Foto (Bootstrap 5) -->
    <div class="modal fade no-print" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="photoModalLabel">Dokumentasi Kegiatan</h5>
                        <p class="text-muted small mb-0" id="modalDate"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="" id="modalImage" class="img-fluid rounded shadow-sm" style="max-height: 75vh; object-fit: contain;" alt="Foto Kegiatan">
                    <div class="mt-3 text-start bg-light p-3 rounded border">
                        <h6 class="fw-bold mb-1 text-secondary">Deskripsi:</h6>
                        <p class="mb-0 text-dark" id="modalTitle"></p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Formal (no-print) -->
    <footer class="footer-bpbd text-center no-print">
        <div class="container">
            <p class="mb-1 fw-semibold text-dark">Badan Penanggulangan Bencana Daerah (BPBD) Daerah Istimewa Yogyakarta</p>
            <p class="mb-0 small text-muted">Sistem Dokumentasi Kegiatan Resmi &bull; Jl. Kenari No. 14A Yogyakarta</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/kegiatan.js') }}"></script>
    @stack('scripts')
</body>
</html>
