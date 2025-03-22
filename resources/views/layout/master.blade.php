{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Hệ thống quản lý')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* Màu chủ đạo theo yêu cầu */
        :root {
            --web-color: #326E51;
        }
        
        /* Các class màu */
        .text-web {
            color: var(--web-color) !important;
        }
        .list-group-item.active {
            background-color: var(--web-color) !important;
            border-color: var(--web-color) !important;
        }
        
        .bg-web {
            background-color: var(--web-color) !important;
        }
        
        .border-web {
            border-color: var(--web-color) !important;
        }
        
        .btn-web {
            background-color: var(--web-color) !important;
            border-color: var(--web-color) !important;
            color: white !important;
        }
        
        .btn-outline-web {
            color: var(--web-color) !important;
            border-color: var(--web-color) !important;
        }
        
        .btn-outline-web:hover {
            background-color: var(--web-color) !important;
            color: white !important;
        }
        
        /* Header styles */
        .navbar-brand {
            font-weight: bold;
        }
        
        /* Sidebar styles */
        .sidebar {
            min-height: calc(100vh - 56px);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link {
            color: #333;
            border-radius: 0;
            padding: 0.8rem 1rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
        }
        
        .sidebar .nav-link.active {
            background-color: var(--web-color);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        /* Main content area */
        .main-content {
            padding: 20px;
        }
        
        /* Footer */
        footer {
            background-color: #f8f9fa;
            padding: 15px 0;
            margin-top: 30px;
        }
        
        /* Card styles */
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            font-weight: bold;
        }
        
        /* Table styles */
        .table-responsive {
            overflow-x: auto;
        }
        
        /* Buttons in forms */
        .form-group button {
            margin-right: 5px;
        }
        
        /* Product cards */
        .product-card {
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            transform: translateY(-5px);
        }
        
        /* Badge giảm giá */
        .discount-badge {
            background-color: #ff5c00;
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            top: 10px;
            left: 10px;
            z-index: 2;
        }
        
        /* Category badge */
        .category-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            background-color: #f8f9fa;
            color: #6c757d;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }
        
        /* Product title */
        .product-title {
            font-size: 0.95rem;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 0.5rem;
        }
        
        /* Đảm bảo ảnh hiển thị đầy đủ */
        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        
        /* Nút thêm vào giỏ hàng */
        .add-to-cart {
            bottom: 10px;
            right: 10px;
            z-index: 2;
        }
        
        /* Dashboard stats */
        .stats-card {
            border-left: 4px solid;
        }
        
        .stats-card.primary {
            border-left-color: var(--web-color);
        }
        
        .stats-card.success {
            border-left-color: var(--web-color);
        }
        
        .stats-card.warning {
            border-left-color: #ffc107;
        }
        
        .stats-card.danger {
            border-left-color: #dc3545;
        }
        
        /* Avatar styles from second master */
        .avatar-container {
            position: relative;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 10px;
        }

        .dropdown-menu .dropdown-item {
            padding: 10px 15px;
            border-radius: 5px;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f2f5;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-info .user-name {
            font-weight: bold;
            font-size: 16px;
        }

        .user-info .user-handle {
            color: #606770;
            font-size: 14px;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Header/Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-web">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('products.user') }}">
                <i class="fas fa-store me-2"></i>
                Shop Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-list"></i> Danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('brands.index') }}">
                            <i class="fas fa-box"></i> Thương hiệu
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-outline-light me-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge bg-danger">0</span>
                    </a>
                    
                    @if (Auth::check())
                        <div class="avatar-container">
                            <img src="{{ asset('images/avatar-placeholder.png') }}" class="avatar" alt="Avatar" data-bs-toggle="dropdown" aria-expanded="false">
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="user-info">
                                    <img src="{{ asset('images/avatar-placeholder.png') }}" alt="User Avatar">
                                    <div>
                                        <div class="user-name">{{ Auth::user()->name }}</div>
                                        <div class="user-handle">{{ Auth::user()->email }}</div>
                                    </div>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Thông tin</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-outline-light me-2">Đăng ký</a>
                        <a href="{{ route('login') }}" class="btn btn-light">Đăng nhập</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for admin area -->
            @if(request()->is('admin*') || request()->is('categories*') || request()->is('products') || request()->is('products/create') || request()->is('products/*/edit'))
            <div class="col-md-2 bg-light sidebar p-0">
                <div class="position-sticky">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Bảng điều khiển
                        </a>
                        <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-list"></i> Quản lý danh mục
                        </a>
                        <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') ? 'active' : '' }}">
                            <i class="fas fa-box"></i> Quản lý sản phẩm
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-users"></i> Quản lý người dùng
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar"></i> Thống kê báo cáo
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog"></i> Cài đặt hệ thống
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-10 main-content">
                @yield('content')
            </div>
            @else
            <div class="col-md-12 main-content">
                @yield('content')
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 bg-light">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Shop Management. Bản quyền thuộc về chúng tôi.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional JavaScript for specific pages -->
    @yield('scripts')
</body>
</html>