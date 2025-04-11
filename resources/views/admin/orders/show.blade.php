@extends('admin.layouts.master')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.orders.export', ['id' => $order->id]) }}" class="btn btn-success">
                <i class="fas fa-file-export"></i> Xuất Excel
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#updateStatusModal">
                <i class="fas fa-edit"></i> Cập nhật trạng thái
            </button>
        </div>
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
    
    <div class="row">
        <div class="col-md-8">
            <!-- Order Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                            <p>
                                <strong>Trạng thái:</strong>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'shipping' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'failed' => 'bg-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClass[$order->status] ?? 'bg-secondary' }}">
                                    {{ $orderStatuses[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </p>
                            <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Cập nhật lần cuối:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            <p>
                                <strong>Thanh toán:</strong>
                                @if($order->payment)
                                    {{ $order->payment->payment_method == 'cod' ? 'Tiền mặt (COD)' : 'VNPay' }}
                                    <span class="badge {{ $order->payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $order->payment->status }}
                                    </span>
                                @else
                                    <span class="text-danger">Không có</span>
                                @endif
                            </p>
                            @if($order->payment && $order->payment->transaction_id)
                                <p><strong>Mã giao dịch:</strong> {{ $order->payment->transaction_id }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold">Thông tin giao hàng</h6>
                    <p><strong>Tên người nhận:</strong> {{ $order->shipping_name }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                    @if($order->note)
                        <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
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
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         width="50" class="me-2" alt="{{ $item->product->name }}">
                                                @endif
                                                <div>
                                                    @if($item->product)
                                                        <div>{{ $item->product->name }}</div>
                                                        <div class="small text-muted">
                                                            ID: {{ $item->product->id }}
                                                            @if($item->product->sku)
                                                                | SKU: {{ $item->product->sku }}
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="text-danger">Sản phẩm đã bị xóa (ID: {{ $item->product_id }})</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ number_format($item->price) }}đ
                                            @if($item->discount > 0)
                                                <div class="small text-danger">-{{ $item->discount }}%</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            @php
                                                $priceAfterDiscount = $item->price * (1 - $item->discount/100);
                                                $subtotal = $priceAfterDiscount * $item->quantity;
                                            @endphp
                                            {{ number_format($subtotal) }}đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Tạm tính</th>
                                    <th class="text-end">{{ number_format($order->subtotal) }}đ</th>
                                </tr>
                                @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="3">Giảm giá
                                            @if($order->coupon)
                                                <span class="small text-muted">({{ $order->coupon->code }})</span>
                                            @endif
                                        </td>
                                        <td class="text-end text-danger">-{{ number_format($order->discount_amount) }}đ</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="3">Tổng cộng</th>
                                    <th class="text-end">{{ number_format($order->total_amount) }}đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Order Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn hàng được tạo</h6>
                                <p class="timeline-text small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- You can add more timeline items based on order statuses and updates -->
                        @if($order->payment && $order->payment->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Thanh toán {{ $order->payment->payment_method == 'cod' ? 'COD' : 'VNPay' }}</h6>
                                    <p class="timeline-text small text-muted">{{ $order->payment->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->status != 'pending' && $order->updated_at && $order->updated_at->ne($order->created_at))
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $order->status == 'cancelled' ? 'bg-danger' : 'bg-primary' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Trạng thái cập nhật thành {{ $orderStatuses[$order->status] }}</h6>
                                    <p class="timeline-text small text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Send Notification Button -->
                    <div class="mt-4">
                        <form action="{{ route('admin.orders.notify', $order) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-bell"></i> Gửi thông báo cho khách hàng
                            </button>
                        </form>
                        
                        <!-- Printable Invoice Link -->
                        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-print"></i> In hóa đơn
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p><strong>Tổng đơn hàng:</strong> {{ $order->user->orders->count() }}</p>
                        <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user"></i> Xem chi tiết khách hàng
                        </a>
                    @else
                        <p class="text-danger">Thông tin người dùng không khả dụng</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Cập nhật trạng thái đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Trạng thái mới</label>
                        <select class="form-control" id="status" name="status" required>
                            @foreach($orderStatuses as $value => $label)
                                <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Lưu ý:
                        <ul class="mb-0">
                            <li>Khi chuyển sang trạng thái <strong>Đã hủy</strong>, số lượng sản phẩm sẽ được hoàn lại vào kho</li>
                            <li>Khi chuyển sang trạng thái <strong>Hoàn thành</strong>, thanh toán sẽ được đánh dấu là hoàn tất</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Timeline styling */
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline:before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: 1px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-top: 3px;
}
.timeline-content {
    padding-left: 15px;
}
.timeline-title {
    margin-bottom: 5px;
    font-size: 1rem;
}
</style>
@endsection