@extends('layout.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <span class="text-muted">Không có hình ảnh</span>
                </div>
            @endif
        </div>
        <div class="col-md-7">
            <h1>{{ $product->name }}</h1>
            <p>
                <span class="badge bg-secondary">{{ $product->category->name }}</span>
            </p>
            
            <div class="mb-3">
                @if ($product->discount > 0)
                    <p class="text-decoration-line-through text-muted mb-0">
                        {{ number_format($product->price, 0, ',', '.') }} đ
                    </p>
                    <h3 class="text-danger">
                        {{ number_format($product->price * (100 - $product->discount) / 100, 0, ',', '.') }} đ
                        <span class="badge bg-danger">-{{ $product->discount }}%</span>
                    </h3>
                @else
                    <h3>{{ number_format($product->price, 0, ',', '.') }} đ</h3>
                @endif
            </div>
            
            <p>Số lượng: {{ $product->quantity }} sản phẩm có sẵn</p>
            
            <div class="mb-4">
                <h4>Mô tả sản phẩm</h4>
                <p>{{ $product->description ?? 'Không có mô tả cho sản phẩm này.' }}</p>
            </div>
            
            <div class="d-grid gap-2 d-md-block">
                <button class="btn btn-primary">Thêm vào giỏ hàng</button>
                <button class="btn btn-outline-secondary">Mua ngay</button>
            </div>
        </div>
    </div>
</div>
@endsection