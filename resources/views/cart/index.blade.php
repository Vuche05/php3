<!-- resources/views/cart/index.blade.php -->
@extends('layout.master')

@section('content')
<div class="container">
    <h1>Giỏ Hàng</h1>

    @if($cartItems->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $item->product->image) }}" width="50">
                        {{ $item->product->name }}
                    </td>
                    <td>
                        @if($item->product->discount)
                            <del>{{ number_format($item->product->price) }}đ</del>
                            {{ number_format($item->product->price * (1 - $item->product->discount/100)) }}đ
                        @else
                            {{ number_format($item->product->price) }}đ
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('cart.update', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}">
                            <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                        </form>
                    </td>
                    <td>
                        {{ number_format($item->total_price) }}đ
                    </td>
                    <td>
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Tổng Cộng:</td>
                    <td>{{ number_format($total) }}đ</td>
                    <td>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">Xóa Giỏ Hàng</button>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
        <a href="#" class="btn btn-success">Thanh Toán</a>
    @else
        <p>Giỏ hàng của bạn đang trống.</p>
    @endif
</div>
@endsection