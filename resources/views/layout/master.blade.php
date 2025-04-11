{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'VSKINCARE - Sản phẩm chăm sóc da')</title>
    
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
        
        /* Multi-level dropdown menu */
        .dropdown-submenu {
            position: absolute;
            top: 0;
            left: 100%;
            margin-top: -1px;
            display: none;
            z-index: 1000;
            min-width: 10rem;
            padding: 0.5rem 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.25rem;
        }

        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu > li:hover > .dropdown-submenu {
            display: block;
        }

        /* Optional: Add a hover effect for all dropdown items */
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--web-color);
        }

        /* Make dropdown items with children display a "right arrow" icon */
        .dropdown-menu .dropdown-item i.fa-chevron-right {
            font-size: 0.75rem;
            margin-top: 0.35rem;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Header/Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-web">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" width="40" height="40" class="me-2">
                VSKINCARE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    {{-- Common Navigation Items --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
            
                    {{-- Category Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-list"></i> Danh mục
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @foreach(App\Models\Category::root()->with('children')->get() as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('home', $category->slug) }}">
                                        {{ $category->name }}
                                        @if($category->children->count() > 0)
                                            <i class="fas fa-chevron-right float-end"></i>
                                        @endif
                                    </a>
                                    @if($category->children->count() > 0)
                                        <ul class="dropdown-menu dropdown-submenu">
                                            @foreach($category->children as $child)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('home', $child->slug) }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-th-list"></i> Tất cả danh mục
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    {{-- My Orders - Only show for logged in users --}}
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('checkout.list') }}">
                            <i class="fas fa-clipboard-list"></i> Đơn hàng của tôi
                        </a>
                    </li>
                    @endauth
                </ul>
                
                <div class="d-flex">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light me-2">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->username }}
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-danger ms-1">Admin</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Quản trị
                                    </a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ url('/profile/address') }}"><i class="fas fa-user-circle me-2"></i>Thông tin</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="bg-web text-white mt-4">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-3">
                    <h5>Về VSKINCARE</h5>
                    <p>Chuyên cung cấp các sản phẩm chăm sóc da chính hãng, chất lượng cao với giá cả hợp lý.</p>
                </div>
                <div class="col-md-3">
                    <h5>Thông tin liên hệ</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận 1, TP HCM</li>
                        <li><i class="fas fa-phone me-2"></i> 0123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i> info@vskincare.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Chính sách</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-white">Chính sách vận chuyển</a></li>
                        <li><a href="#" class="text-white">Chính sách đổi trả</a></li>
                        <li><a href="#" class="text-white">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Kết nối với chúng tôi</h5>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-tiktok fa-2x"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center py-3 border-top border-light">
            <p class="mb-0">© 2025 VSKINCARE. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional JavaScript for specific pages -->
    @yield('scripts')
</body>
</html>