@extends('layout.master')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Danh sách đơn hàng của bạn</h2>
            
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
            
            @if($orders->isEmpty())
                <div class="alert alert-info">
                    Bạn chưa có đơn hàng nào. <a href="{{ route('products.index') }}">Tiếp tục mua sắm</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Phương thức thanh toán</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                <td>
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
                                </td>
                                <td>
                                    @if($order->payment)
                                        @if($order->payment->payment_method == 'cod')
                                            <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                                        @elseif($order->payment->payment_method == 'vnpay')
                                            <span class="badge bg-primary">VNPay</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->payment->payment_method }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('checkout.show', $order) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                    
                                    @if($order->status == 'pending')
                                        <form action="{{ route('checkout.cancel', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status == 'completed' || $order->status == 'cancelled')
                                        <a href="{{ route('checkout.rebuy', $order) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-redo"></i> Mua lại
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection