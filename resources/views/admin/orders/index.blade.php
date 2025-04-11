@extends('admin.layouts.master')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
        <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-sm btn-success">
            <i class="fas fa-file-export"></i> Xuất Excel
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
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
                <div class="btn-group">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                        Tất cả
                    </a>
                    @foreach($orderStatuses as $key => $label)
                        <a href="{{ route('admin.orders.index', ['status' => $key]) }}" 
                           class="btn btn-sm {{ request('status') == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <div>{{ $order->shipping_name }}</div>
                                    <div class="small text-muted">{{ $order->shipping_phone }}</div>
                                </td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>
                                    @if($order->payment)
                                        <span class="badge {{ $order->payment->payment_method == 'cod' ? 'bg-secondary' : 'bg-info' }}">
                                            {{ $order->payment->payment_method == 'cod' ? 'COD' : 'VNPay' }}
                                        </span>
                                        <span class="badge {{ $order->payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $order->payment->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Không có</span>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection