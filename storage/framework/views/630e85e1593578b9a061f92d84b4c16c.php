
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'Hệ thống quản lý'); ?></title>
    
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
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Header/Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-web">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(asset('img/logo.png')); ?>" alt="Logo" width="40" height="40" class="me-2">
                VSKINCARE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('home')); ?>">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
            
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-list"></i> Danh mục
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <?php $__currentLoopData = App\Models\Category::root()->with('children')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('home', $category->slug)); ?>">
                                        <?php echo e($category->name); ?>

                                        <?php if($category->children->count() > 0): ?>
                                            <i class="fas fa-chevron-right float-end"></i>
                                        <?php endif; ?>
                                    </a>
                                    <?php if($category->children->count() > 0): ?>
                                        <ul class="dropdown-menu dropdown-submenu">
                                            <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('home', $child->slug)); ?>">
                                                        <?php echo e($child->name); ?>

                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('home')); ?>">
                                    <i class="fas fa-th-list"></i> Tất cả danh mục
                                </a>
                            </li>
                        </ul>
                    </li>
            
                    
                    <?php if(Auth::check() && Auth::user()->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('products.index')); ?>">
                                <i class="fas fa-box"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('brands.index')); ?>">
                                <i class="fas fa-star"></i> Thương hiệu
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    
                    <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('checkout.list')); ?>">
                            <i class="fas fa-clipboard-list"></i> Đơn hàng của tôi
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex">
                    <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-light me-2">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light me-2">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-light">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo e(Auth::user()->username); ?>

                                <?php if(Auth::user()->isAdmin()): ?>
                                    <span class="badge bg-danger ms-1">Admin</span>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if(Auth::user()->isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('categories.index')); ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Quản trị
                                    </a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo e(url('/profile/address')); ?>"><i class="fas fa-user-circle me-2"></i>Thông tin</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    
    <?php if(Auth::check() && Auth::user()->isAdmin()): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light sidebar p-0">
                <div class="position-sticky">
                    <div class="list-group list-group-flush">
                        
                        <a href="<?php echo e(route('categories.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                            <i class="fas fa-list"></i> Quản lý danh mục
                        </a>
                        <a href="<?php echo e(route('brands.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('brands.index') || request()->routeIs('brands.create') || request()->routeIs('brands.edit') ? 'active' : ''); ?>">
                            <i class="fas fa-star"></i> Quản lý thương hiệu
                        </a>
                        <a href="<?php echo e(route('products.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') ? 'active' : ''); ?>">
                            <i class="fas fa-box"></i> Quản lý sản phẩm
                        </a>
                        <a href="<?php echo e(route('coupon.index')); ?>" class="list-group-item list-group-item-action" <?php echo e(request()->routeIs('coupons.index') || request()->routeIs('coupons.create') || request()->routeIs('coupons.edit') ? 'active' : ''); ?>>
                            <i class="fas fa-tags"></i> Quản lý mã giảm giá
                        </a>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>">
                            <i class="fas fa-shopping-bag"></i> Quản lý đơn hàng
                        </a>
                        <a href="<?php echo e(route('users.index')); ?>" class="list-group-item list-group-item-action">
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
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 main-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional JavaScript for specific pages -->
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/layout/master.blade.php ENDPATH**/ ?>