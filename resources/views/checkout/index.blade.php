@extends('layout.master')

@section('content')
<div class="container">
    <h1>Thanh Toán</h1>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    Thông Tin Giao Hàng
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="shipping_name">Họ và Tên</label>
                            <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" 
                                   id="shipping_name" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}" required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="shipping_address">Địa Chỉ Giao Hàng</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="shipping_phone">Số Điện Thoại</label>
                            <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                   id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="note">Ghi Chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="2">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label>Phương Thức Thanh Toán</label>
                            <div class="payment-methods mt-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    </label>
                                    <div class="text-muted small">Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng</div>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                                    <label class="form-check-label" for="vnpay">
                                        <strong>Thanh toán qua VNPay</strong>
                                    </label>
                                    <div class="text-muted small">Thanh toán an toàn với thẻ ATM, Visa, MasterCard qua cổng VNPay</div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Xác Nhận Đặt Hàng</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    Mã Giảm Giá
                </div>
                <div class="card-body">
                    <form action="{{ route('coupon.apply') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control me-2" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="btn btn-outline-primary">Áp dụng</button>
                    </form>
                    
                    @if(session('coupon'))
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Mã: <strong>{{ session('coupon')['code'] }}</strong></span>
                                <a href="{{ route('coupon.remove') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    Đơn Hàng Của Bạn
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" width="40" class="me-2">
                                            <div>
                                                <div>{{ $item->product->name }}</div>
                                                <div class="text-muted small">SL: {{ $item->quantity }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        @if($item->product->discount)
                                            {{ number_format($item->product->price * (1 - $item->product->discount/100) * $item->quantity) }}đ
                                        @else
                                            {{ number_format($item->product->price * $item->quantity) }}đ
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tạm tính</th>
                                <th class="text-end">{{ number_format($total) }}đ</th>
                            </tr>
                            
                            @if(session('coupon'))
                            <tr>
                                <td>Giảm giá</td>
                                <td class="text-end text-danger">- {{ number_format(session('coupon')['discount']) }}đ</td>
                            </tr>
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end">{{ number_format($total - session('coupon')['discount']) }}đ</th>
                            </tr>
                            @else
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end">{{ number_format($total) }}đ</th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection