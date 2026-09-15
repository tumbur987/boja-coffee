<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('site_name', 'SiBoja') }} - @yield('title')</title>
    @if(!empty(\App\Models\Setting::get('site_favicon')))
        <link rel="icon" href="{{ asset('storage/' . \App\Models\Setting::get('site_favicon')) }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <style>
        :root {
            --coffee-dark: #2c1810;
            --coffee: #4a2c2a;
            --coffee-light: #6b4226;
            --cream: #d4a574;
            --cream-light: #f5e6d3;
            --cream-lighter: #faf3eb;
            --bg: #f8f5f1;
            --card-bg: #ffffff;
            --text: #2c1810;
            --text-muted: #8b7355;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --shadow-sm: 0 1px 3px rgba(44,24,16,0.06);
            --shadow: 0 4px 12px rgba(44,24,16,0.08);
            --shadow-lg: 0 10px 30px rgba(44,24,16,0.12);
            --radius: 12px;
            --radius-sm: 8px;
            --radius-xs: 6px;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text); }

        /* Sidebar */
        .main-sidebar { background: linear-gradient(180deg, var(--coffee-dark) 0%, var(--coffee) 100%) !important; border-right: none !important; }
        .main-sidebar .brand-link { border-bottom: 1px solid rgba(255,255,255,0.08) !important; padding: 16px 20px !important; }
        .main-sidebar .brand-link .brand-text { font-weight: 800 !important; font-size: 18px !important; letter-spacing: -0.5px; }
        .sidebar-dark-primary .user-panel { border-bottom: 1px solid rgba(255,255,255,0.08); }
        .sidebar-dark-primary .user-panel a { color: rgba(255,255,255,0.85) !important; font-weight: 600; }
        .sidebar-dark-primary .user-panel .image img { border: 2px solid var(--cream); }
        .nav-sidebar > .nav-item > .nav-link { border-radius: var(--radius-sm) !important; margin: 2px 10px !important; padding: 10px 16px !important; color: rgba(255,255,255,0.65) !important; font-weight: 500 !important; transition: all 0.2s; }
        .nav-sidebar > .nav-item > .nav-link:hover { background: rgba(212,165,116,0.15) !important; color: var(--cream) !important; }
        .nav-sidebar > .nav-item > .nav-link.active { background: var(--cream) !important; color: var(--coffee-dark) !important; box-shadow: 0 4px 12px rgba(212,165,116,0.3) !important; font-weight: 700 !important; }
        .nav-sidebar > .nav-item > .nav-link.active .nav-icon { color: var(--coffee-dark) !important; }
        .nav-header { color: rgba(255,255,255,0.35) !important; font-size: 11px !important; font-weight: 700 !important; letter-spacing: 1.5px !important; text-transform: uppercase !important; padding: 16px 26px 6px !important; }

        /* Content wrapper */
        .content-wrapper { background: var(--bg) !important; }
        .content-header .container-fluid { padding: 20px 24px 0; }
        .content-header h1 { font-size: 22px !important; font-weight: 800 !important; color: var(--coffee-dark) !important; letter-spacing: -0.5px; }
        .breadcrumb { background: transparent !important; padding: 0 !important; margin: 0 !important; }
        .breadcrumb-item a { color: var(--cream) !important; text-decoration: none; font-weight: 600; }
        .breadcrumb-item.active { color: var(--text-muted) !important; }
        .content { padding: 20px 24px !important; }

        /* Cards */
        .card { border: none !important; border-radius: var(--radius) !important; box-shadow: var(--shadow-sm) !important; background: var(--card-bg) !important; overflow: hidden; }
        .card-header { background: var(--card-bg) !important; border-bottom: 1px solid rgba(44,24,16,0.06) !important; padding: 16px 20px !important; border-radius: var(--radius) var(--radius) 0 0 !important; }
        .card-header .card-title { font-weight: 700 !important; color: var(--coffee-dark) !important; margin: 0 !important; }
        .card-body { padding: 20px !important; }

        /* Tables */
        .table { font-size: 14px !important; }
        .table thead th { background: var(--cream-lighter) !important; color: var(--coffee-dark) !important; font-weight: 700 !important; border: none !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; padding: 12px 16px !important; }
        .table tbody td { padding: 12px 16px !important; vertical-align: middle !important; border-color: rgba(44,24,16,0.05) !important; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: var(--cream-lighter) !important; }
        .table-bordered td, .table-bordered th { border: 1px solid rgba(44,24,16,0.06) !important; }

        /* Buttons */
        .btn-primary { background: var(--coffee) !important; border-color: var(--coffee) !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; padding: 8px 18px !important; box-shadow: 0 2px 6px rgba(74,44,42,0.25) !important; transition: all 0.2s !important; }
        .btn-primary:hover { background: var(--coffee-dark) !important; border-color: var(--coffee-dark) !important; transform: translateY(-1px) !important; box-shadow: 0 4px 12px rgba(74,44,42,0.35) !important; }
        .btn-warning { background: var(--warning) !important; border-color: var(--warning) !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; }
        .btn-danger { background: var(--danger) !important; border-color: var(--danger) !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; }
        .btn-success { background: var(--success) !important; border-color: var(--success) !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; }
        .btn-info { background: var(--info) !important; border-color: var(--info) !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; }
        .btn-secondary { background: #6b7280 !important; border-color: #6b7280 !important; border-radius: var(--radius-xs) !important; font-weight: 600 !important; }
        .btn-sm { padding: 6px 12px !important; font-size: 12px !important; }

        /* Forms */
        .form-control { border-radius: var(--radius-xs) !important; border: 2px solid rgba(44,24,16,0.1) !important; padding: 10px 14px !important; font-size: 14px !important; transition: all 0.2s !important; }
        .form-control:focus { border-color: var(--cream) !important; box-shadow: 0 0 0 3px rgba(212,165,116,0.15) !important; }
        .form-group label { font-weight: 600 !important; color: var(--coffee-dark) !important; font-size: 13px !important; margin-bottom: 6px !important; }
        select.form-control { appearance: auto !important; }

        /* Modals */
        .modal-content { border: none !important; border-radius: var(--radius) !important; box-shadow: var(--shadow-lg) !important; animation: modalIn 0.25s ease-out; }
        .modal-header { border-bottom: 1px solid rgba(44,24,16,0.06) !important; padding: 18px 24px !important; }
        .modal-header .modal-title { font-weight: 700 !important; color: var(--coffee-dark) !important; font-size: 17px !important; }
        .modal-header .close { font-size: 22px !important; padding: 4px 8px !important; border-radius: var(--radius-xs) !important; transition: all 0.2s !important; }
        .modal-header .close:hover { background: var(--cream-lighter) !important; }
        .modal-body { padding: 24px !important; overflow-y: auto; }
        .modal-footer { border-top: 1px solid rgba(44,24,16,0.06) !important; padding: 16px 24px !important; }
        .modal-backdrop.show { backdrop-filter: blur(4px); background: rgba(44,24,16,0.4) !important; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }

        /* Badges */
        .badge { font-weight: 600 !important; padding: 5px 10px !important; border-radius: 6px !important; font-size: 11px !important; }
        .badge-primary { background: rgba(74,44,42,0.1) !important; color: var(--coffee) !important; }
        .badge-success { background: rgba(34,197,94,0.1) !important; color: #16a34a !important; }
        .badge-danger { background: rgba(239,68,68,0.1) !important; color: #dc2626 !important; }
        .badge-warning { background: rgba(245,158,11,0.1) !important; color: #d97706 !important; }
        .badge-info { background: rgba(59,130,246,0.1) !important; color: #2563eb !important; }
        .badge-secondary { background: rgba(107,114,128,0.1) !important; color: #4b5563 !important; }

        /* Alert overrides - hidden, we use SweetAlert2 */
        .alert { display: none !important; }

        /* Navbar */
        .main-header.navbar { background: var(--card-bg) !important; border-bottom: 1px solid rgba(44,24,16,0.06) !important; box-shadow: var(--shadow-sm) !important; }
        .main-header .nav-link { color: var(--text) !important; font-weight: 500 !important; }

        /* Footer */
        .main-footer { background: var(--card-bg) !important; border-top: 1px solid rgba(44,24,16,0.06) !important; color: var(--text-muted) !important; font-size: 13px !important; padding: 14px 24px !important; }

        /* Preloader */
        .preloader { background: var(--coffee-dark) !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--cream); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--coffee-light); }

        /* Animation */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .content .card { animation: fadeInUp 0.3s ease-out; }

        /* Item card (transaction modal) */
        .item-card { background: var(--cream-lighter); border: 1px solid rgba(44,24,16,0.06); border-radius: var(--radius-sm); padding: 14px; transition: all 0.2s; margin-bottom: 10px; }
        .item-card:hover { border-color: var(--cream); }
        .item-card .form-control { background: #fff !important; }
        #create-items-container, #edit-items-container { max-height: 320px; overflow-y: auto; padding-right: 4px; }

        /* Pagination */
        .pagination { margin: 0 !important; gap: 4px; display: flex; align-items: center; flex-wrap: wrap; }
        .page-item { list-style: none; }
        .page-item .page-link { border: none !important; border-radius: var(--radius-xs) !important; color: var(--coffee) !important; font-weight: 600 !important; font-size: 13px !important; padding: 7px 12px !important; transition: all 0.2s !important; display: flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; text-decoration: none !important; }
        .page-item .page-link:hover { background: var(--cream-lighter) !important; color: var(--coffee-dark) !important; }
        .page-item.active .page-link { background: var(--coffee) !important; color: #fff !important; box-shadow: 0 2px 8px rgba(74,44,42,0.25) !important; }
        .page-item.disabled .page-link { color: var(--text-muted) !important; opacity: 0.4; pointer-events: none; }
        @media (max-width: 576px) {
            .pagination-wrap { flex-direction: column; gap: 10px; align-items: center !important; }
        }
    </style>
    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            @if(!empty(\App\Models\Setting::get('site_logo')))
                <img class="animation__shake" src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'SiBoja') }}" height="50" style="border-radius: 10px;">
            @else
                <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="SiBoja" height="50" width="50">
            @endif
        </div>

        @include('layouts.admin.navbar')
        @include('layouts.admin.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        @include('layouts.admin.footer')
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>$.widget.bridge('uibutton', $.ui.button)</script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                background: '#fff',
                customClass: { popup: 'swal2-popup-custom' }
            });
        @endif
        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session("error") }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
        function alertSuccess(msg) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: msg, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        }
        function alertError(msg) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: msg, showConfirmButton: false, timer: 4000, timerProgressBar: true });
        }
        function confirmDelete(callback) {
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(r) { if (r.isConfirmed) callback(); });
        }
        document.querySelectorAll('.modal form').forEach(function(form) {
            form.addEventListener('submit', function() {
                var btn = form.querySelector('.modal-footer button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    var original = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
                    setTimeout(function() { btn.disabled = false; btn.innerHTML = original; }, 5000);
                }
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
