@extends('layout.master')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
                <a href="{{ route('checkout.list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p>
                                <strong>Trạng thái:</strong>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">Đang xử lý</span>
                                        @break
                                    @case('shipping')
                                        <span class="badge bg-primary">Đang giao hàng</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Thất bại</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endswitch
                            </p>
                            
                            @if($order->payment)
                                <p>
                                    <strong>Phương thức thanh toán:</strong>
                                    @if($order->payment->payment_method == 'cod')
                                        Thanh toán khi nhận hàng (COD)
                                    @elseif($order->payment->payment_method == 'vnpay')
                                        VNPay
                                    @else
                                        {{ $order->payment->payment_method }}
                                    @endif
                                </p>
                                <p>
                                    <strong>Trạng thái thanh toán:</strong>
                                    @switch($order->payment->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Chờ thanh toán</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">Thất bại</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->payment->status }}</span>
                                    @endswitch
                                </p>
                            @endif
                            
                            @if($order->note)
                                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                            
                            @if($freeShipping)
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-truck me-2"></i> <strong>Miễn phí vận chuyển</strong>
                                </div>
                            @endif
                            
                            @if($order->status == 'shipping')
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-shipping-fast me-2"></i> <strong>Thông tin vận chuyển:</strong><br>
                                    Đơn hàng của bạn đang được giao đến địa chỉ đã đăng ký.<br>
                                    Dự kiến giao hàng: {{ now()->addDays(3)->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Giảm giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">
                                                    @if($item->product)
                                                        <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Sản phẩm không còn tồn tại</span>
                                                    @endif
                                                </h6>
                                                @if($item->product && $item->product->sku)
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                    <td>
                                        @if($item->discount > 0)
                                            {{ $item->discount }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">
                                        @php
                                            $itemTotal = $item->price * (1 - $item->discount/100) * $item->quantity;
                                        @endphp
                                        {{ number_format($itemTotal, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tạm tính:</strong></td>
                                    <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                    <td class="text-end">
                                        @if($freeShipping || ($order->shipping_cost ?? 0) == 0)
                                            <span class="text-success">Miễn phí</span>
                                        @else
                                            {{ number_format($order->shipping_cost ?? 30000, 0, ',', '.') }}₫
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end fw-bold fs-5">
                                        {{ number_format($order->total_amount + ($order->shipping_cost ?? 0), 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('checkout.list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                
                <div>
                    @if($order->status == 'pending')
                        <form action="{{ route('checkout.cancel', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                <i class="fas fa-times"></i> Hủy đơn hàng
                            </button>
                        </form>
                    @endif

                    @if($order->status == 'completed' || $order->status == 'cancelled')
                        <a href="{{ route('checkout.rebuy', $order) }}" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Mua lại
                        </a>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, form, nav, footer {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush