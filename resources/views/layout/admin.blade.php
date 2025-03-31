{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --web-color: #326E51; }
        .text-web { color: var(--web-color) !important; }
        .bg-web { background-color: var(--web-color) !important; }
        .btn-web { background-color: var(--web-color) !important; color: white !important; }
        .sidebar { min-height: 100vh; background: #f8f9fa; }
    </style>

    @yield('styles')
</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-web">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="fas fa-store"></i> Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                </ul>
                <a href="{{ route('logout') }}" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-3">
                <ul class="list-group">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item"><i class="fas fa-chart-line"></i> Bảng điều khiển</a>
                    <a href="{{ route('categories.index') }}" class="list-group-item"><i class="fas fa-list"></i> Danh mục</a>
                    <a href="{{ route('products.index') }}" class="list-group-item"><i class="fas fa-box"></i> Sản phẩm</a>
                    <a href="{{ route('brands.index') }}" class="list-group-item"><i class="fas fa-box"></i> Thương hiệu</a>
                    <a href="{{ route('users.index') }}" class="list-group-item"><i class="fas fa-users"></i> Người dùng</a>
                    <a href="#" class="list-group-item"><i class="fas fa-cog"></i> Cài đặt</a>
                </ul>
            </div>
            <div class="col-md-10 p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 bg-light">
        <p>&copy; {{ date('Y') }} Admin Panel</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
