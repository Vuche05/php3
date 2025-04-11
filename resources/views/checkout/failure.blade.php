@extends('layout.master')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa fa-times-circle text-danger" style="font-size: 64px;"></i>
        </div>
        <h1>Đặt Hàng Thất Bại</h1>
        <p class="lead text-danger">{{ $message }}</p>
    </div>
    
    @if(isset($order))
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Chi tiết đơn hàng #{{ $order->id }}</span>
                <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="card-body">
            <p>Đơn hàng của bạn không thể hoàn tất do lỗi thanh toán.</p>
            <p>Vui lòng thử lại hoặc chọn phương thức thanh toán khác.</p>
        </div>
    </div>
    @endif
    
    <div class="text-center">
        <a href="{{ route('cart.index') }}" class="btn btn-primary">Quay lại giỏ hàng</a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
    </div>
</div>
@endsection