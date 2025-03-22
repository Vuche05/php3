@extends('layout.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-web">Trang Chủ</h1>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('home') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                    <select name="category" class="form-select" style="max-width: 150px;">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-web" type="submit">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold fs-4 mb-0 position-relative">
                    <span class="border-bottom border-3 border-web pb-2">Sản Phẩm</span>
                </h2>
                <a href="{{ route('products.user') }}" class="text-decoration-none text-web">Xem tất cả <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Show search results if search is active -->
    @if(request('search') || request('category'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                Kết quả tìm kiếm {{ request('search') ? 'cho "' . request('search') . '"' : '' }}
                {{ request('category') && request('search') ? ' trong danh mục' : (request('category') ? 'trong danh mục' : '') }}
                {{ request('category') ? ' "' . $categories->firstWhere('id', request('category'))->name . '"' : '' }}
                <a href="{{ route('home') }}" class="float-end"><i class="fas fa-times"></i> Xóa bộ lọc</a>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        @forelse ($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-6 mb-4">
                <div class="card border-0 shadow-sm h-100 position-relative product-card">
                    @if ($product->discount > 0)
                        <div class="position-absolute discount-badge">
                            -{{ $product->discount }}%
                        </div>
                    @endif
                    <div class="position-relative">
                        @if ($product->image)
                            <a href="{{ route('product.show', $product) }}">
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            </a>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No image</span>
                            </div>
                        @endif
                        <div class="position-absolute add-to-cart">
                            <button class="btn btn-sm btn-web rounded-circle" title="Thêm vào giỏ hàng">
                                <a href="{{ route('product.show', $product) }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>                                
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <span class="category-badge">{{ $product->category->name }}</span>
                        <h5 class="card-title product-title">
                            <a href="{{ route('product.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h5>
                        @if ($product->discount > 0)
                            <div class="d-flex flex-column mb-2">
                                <span class="text-danger fw-bold fs-5">
                                    {{ number_format($product->price * (100 - $product->discount) / 100, 0, ',', '.') }} đ
                                </span>
                                <span class="text-decoration-line-through text-muted small">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </span>
                            </div>
                        @else
                            <div class="mb-2">
                                <span class="fw-bold fs-5">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                            </div>
                        @endif
                        <a href="{{ route('product.show', $product) }}" class="btn btn-outline-web btn-sm w-100">Mua Ngay</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Không có sản phẩm nào hiển thị.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $featuredProducts->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Màu chủ đạo theo yêu cầu */
    :root {
        --web-color: #326E51;
    }
    
    /* Các class màu */
    .text-web {
        color: var(--web-color) !important;
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
    
    /* Hiệu ứng hover */
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
    
    /* Nút thêm vào giỏ hàng */
    .add-to-cart {
        bottom: 10px;
        right: 10px;
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
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item.active .page-link {
        background-color: var(--web-color) !important;
        border-color: var(--web-color) !important;
    }
    
    .page-link {
        color: var(--web-color) !important;
    }
    
    .page-link:hover {
        color: var(--web-color) !important;
    }
</style>
@endsection

@section('scripts')
@endsection