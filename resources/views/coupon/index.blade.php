@extends('layout.master')
<style>
    .badge-success {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.875rem;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.875rem;
    }

    .table thead th {
        background-color: #f8f9fa;
        text-align: center;
        vertical-align: middle;
    }

    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }

    .btn-group .btn {
        margin: 0 3px;
        padding: 4px 8px;
        font-size: 0.875rem;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }

    .btn-info:hover, .btn-danger:hover {
        opacity: 0.85;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .alert {
        font-size: 0.95rem;
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý mã giảm giá</h3>
                    <div class="card-tools">
                        <a href="{{ route('coupon.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mã giảm giá
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Đơn hàng tối thiểu</th>
                                    <th>Giảm tối đa</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Giới hạn sử dụng</th>
                                    <th>Đã dùng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->type === 'fixed' ? 'Cố định' : 'Phần trăm' }}</td>
                                    <td>{{ $coupon->formatted_value }}</td>
                                    <td>{{ $coupon->min_order_amount ? number_format($coupon->min_order_amount, 0, ',', '.') . 'đ' : 'Không' }}</td>
                                    <td>{{ $coupon->max_discount_amount ? number_format($coupon->max_discount_amount, 0, ',', '.') . 'đ' : 'Không' }}</td>
                                    <td>{{ $coupon->starts_at ? $coupon->starts_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                                    <td>{{ $coupon->usage_limit ?: 'Không giới hạn' }}</td>
                                    <td>{{ $coupon->used_count }}</td>
                                    <td>
                                        @if($coupon->is_active && 
                                           (!$coupon->starts_at || $coupon->starts_at <= now()) && 
                                           (!$coupon->expires_at || $coupon->expires_at >= now()) && 
                                           (!$coupon->usage_limit || $coupon->used_count < $coupon->usage_limit))
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-danger">Không hợp lệ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('coupon.edit', $coupon) }}" class="btn btn-sm btn-edit" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>                                            
                                            <form action="{{ route('coupon.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Không có mã giảm giá nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection