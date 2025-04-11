@extends('layout.master')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
        </div>
        <h1>Đặt Hàng Thành Công!</h1>
        <p class="lead">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đang được xử lý.</p>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Chi tiết đơn hàng #{{ $order->id }}</span>
                <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin giao hàng</h5>
                    <p>
                        <strong>Người nhận:</strong> {{ $order->shipping_name }}<br>
                        <strong>Địa chỉ:</strong> {{ $order->shipping_address }}<br>
                        <strong>Số điện thoại:</strong> {{ $order->shipping_phone }}
                    </p>
                    
                    @if($order->note)
                        <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>Thông tin thanh toán</h5>
                    <p>
                        <strong>Phương thức:</strong> 
                        @if($order->payment->payment_method == 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @else
                            Thanh toán qua VNPay
                        @endif
                        <br>
                        <strong>Trạng thái:</strong> 
                        @if($order->payment->status == 'completed')
                            <span class="text-success">Đã thanh toán</span>
                        @elseif($order->payment->status == 'pending')
                            <span class="text-warning">Chờ thanh toán</span>
                        @else
                            <span class="text-danger">Thất bại</span>
                        @endif
                        <br>
                        @if($order->discount_amount > 0)
                            <div class="row mb-2">
                                <div class="col-6">Mã giảm giá:</div>
                                <div class="col-6 text-end">{{ $order->coupon->code ?? 'N/A' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Giảm giá:</div>
                                <div class="col-6 text-end text-danger">- {{ number_format($order->discount_amount) }}đ</div>
                            </div>
                        @endif
                        <strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}đ
                    </p>
                </div>
            </div>
            
            <h5>Sản phẩm đã đặt</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="40" class="me-2">
                                    {{ $item->product->name }}
                                </div>
                            </td>
                            <td>
                                @if($item->discount)
                                    <del>{{ number_format($item->price) }}đ</del>
                                    {{ number_format($item->price * (1 - $item->discount/100)) }}đ
                                @else
                                    {{ number_format($item->price) }}đ
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end">
                                @if($item->discount)
                                    {{ number_format($item->price * (1 - $item->discount/100) * $item->quantity) }}đ
                                @else
                                    {{ number_format($item->price * $item->quantity) }}đ
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Tổng cộng</th>
                        <th class="text-end">{{ number_format($order->total_amount) }}đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="{{ route('checkout.list') }}" class="btn btn-outline-secondary">Xem tất cả đơn hàng</a>
    </div>
</div>
@endsection