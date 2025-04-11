@extends('layout.master')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết đơn hàng #{{ $order->order_number }}</h5>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light">Quay lại</a>
        </div>
        
        <div class="card-body">
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
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Thông tin đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="ps-0">Mã đơn hàng:</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Ngày đặt hàng:</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Trạng thái:</th>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'shipping' => 'bg-primary',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                            ][$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $order->status_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Phương thức thanh toán:</th>
                                    <td>{{ $order->payment_method_name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Thông tin giao hàng</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="ps-0">Họ tên:</th>
                                    <td>{{ $order->shipping_name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Địa chỉ:</th>
                                    <td>{{ $order->shipping_address }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Số điện thoại:</th>
                                    <td>{{ $order->shipping_phone }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Email:</th>
                                    <td>{{ $order->shipping_email }}</td>
                                </tr>
                                @if($order->notes)
                                <tr>
                                    <th class="ps-0">Ghi chú:</th>
                                    <td>{{ $order->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Chi tiết sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light me-3" style="width: 50px; height: 50px;"></div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product ? $item->product->name : 'Sản phẩm không còn tồn tại' }}</h6>
                                                    @if($item->discount > 0)
                                                        <small class="text-muted">Giảm giá: {{ $item->discount }}%</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ number_format($item->final_price, 0, ',', '.') }}đ</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($order->total_amount + $order->discount_amount, 0, ',', '.') }}đ</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Giảm giá:</td>
                                    <td class="text-end text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($order->status === 'pending')
                <div class="text-center">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                    </form>
                </div>
            @elseif($order->payment_method === 'bank_transfer' && $order->status !== 'cancelled')
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0">Thông tin chuyển khoản</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">Ngân hàng: VCB - Ngân hàng TMCP Ngoại thương Việt Nam</p>
                        <p class="mb-1">Số tài khoản: 1234567890</p>
                        <p class="mb-1">Chủ tài khoản: CÔNG TY TNHH ABC</p>
                        <p class="mb-0">Nội dung: Thanh toán đơn hàng {{ $order->order_number }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection