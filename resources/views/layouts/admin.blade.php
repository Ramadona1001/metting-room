<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - نظام حجز القاعات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .sidebar { 
            min-height: 100vh; 
            background: #1e1b4b; 
            position: fixed;
            top: 0;
            right: 0;
            width: 250px;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link i { margin-left: 10px; }
        .main-content { 
            background: #f8f9fa; 
            min-height: 100vh; 
            margin-right: 250px;
            transition: margin 0.3s ease;
        }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); }
        .mobile-header { 
            display: none; 
            background: #1e1b4b; 
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1001;
            padding: 10px 15px;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        @media (max-width: 991.98px) {
            .sidebar { 
                transform: translateX(100%); 
                width: 280px;
            }
            .sidebar.show { transform: translateX(0); }
            .main-content { 
                margin-right: 0; 
                padding-top: 70px;
            }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; }
            .sidebar-overlay.show { display: block; }
        }
        @media (max-width: 575.98px) {
            .btn-group { flex-wrap: wrap; gap: 2px; }
            .table-responsive { font-size: 0.875rem; }
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">
        <h5 class="text-white mb-0">نظام حجز القاعات</h5>
        <button class="btn btn-link text-white p-0" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3 text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">نظام حجز القاعات</h5>
            <button class="btn btn-link text-white p-0 d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" href="{{ route('admin.companies.index') }}">
                    <i class="bi bi-building"></i> الشركات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                    <i class="bi bi-diagram-3"></i> الأقسام
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.meeting-rooms.*') ? 'active' : '' }}" href="{{ route('admin.meeting-rooms.index') }}">
                    <i class="bi bi-door-open"></i> غرف الاجتماعات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <i class="bi bi-calendar-check"></i> الحجوزات
                </a>
            </li>
        </ul>
        <div class="position-absolute bottom-0 w-100 p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content py-4 px-3 px-md-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        }
    </script>
    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateDeleteButton();
        }

        function updateDeleteButton() {
            const checked = document.querySelectorAll('.select-item:checked').length;
            const btn = document.getElementById('multi-delete-btn');
            if (btn) {
                btn.disabled = checked === 0;
                btn.textContent = checked > 0 ? `حذف المحدد (${checked})` : 'حذف المحدد';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select-item').forEach(cb => {
                cb.addEventListener('change', updateDeleteButton);
            });
        });

        function submitMultiDelete(formId) {
            const checked = document.querySelectorAll('.select-item:checked');
            if (checked.length === 0) {
                alert('الرجاء تحديد عناصر للحذف');
                return false;
            }
            if (confirm(`هل أنت متأكد من حذف ${checked.length} عنصر؟`)) {
                document.getElementById(formId).submit();
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
