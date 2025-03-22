{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.master')

@section('layout')
<!-- Sidebar for admin area -->
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
@endsection