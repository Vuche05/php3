@extends('layout.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="product-gallery">
                <!-- Main image display -->
                <div class="main-image mb-3">
                    @if ($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" class="img-fluid" alt="{{ $product->name }}">
                    @elseif ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <span class="text-muted">Không có hình ảnh</span>
                        </div>
                    @endif
                </div>
                
                <!-- Thumbnail images -->
                @if ($product->images && $product->images->count() > 0)
                    <div class="thumbnail-images d-flex flex-wrap">
                        @foreach($product->images as $image)
                            <div class="thumbnail-item me-2 mb-2" style="width: 80px; cursor: pointer;">
                                <img 
                                    src="{{ asset('storage/' . $image->image_path) }}" 
                                    class="img-thumbnail {{ $image->is_primary ? 'border-primary' : '' }}"
                                    alt="Thumbnail" 
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
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
                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-web">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
        <!-- Sản phẩm liên quan -->
<div class="related-products mt-5">
    <h3>Sản phẩm liên quan</h3>
    <div class="row mt-4">
        @forelse($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="product-image-container" style="height: 200px; overflow: hidden;">
                        @if ($relatedProduct->primaryImage)
                            <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image_path) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="object-fit: cover; height: 100%;">
                        @elseif ($relatedProduct->image)
                            <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="object-fit: cover; height: 100%;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <span class="text-muted">Không có hình ảnh</span>
                            </div>
                        @endif
                        
                        @if ($relatedProduct->discount > 0)
                            <div class="position-absolute top-0 end-0 bg-danger text-white p-1">
                                -{{ $relatedProduct->discount }}%
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        
                        <div class="mt-auto">
                            @if ($relatedProduct->discount > 0)
                                <p class="text-decoration-line-through text-muted mb-0">
                                    {{ number_format($relatedProduct->price, 0, ',', '.') }} đ
                                </p>
                                <p class="text-danger fw-bold mb-2">
                                    {{ number_format($relatedProduct->price * (100 - $relatedProduct->discount) / 100, 0, ',', '.') }} đ
                                </p>
                            @else
                                <p class="fw-bold mb-2">{{ number_format($relatedProduct->price, 0, ',', '.') }} đ</p>
                            @endif
                            
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Không có sản phẩm liên quan.</p>
            </div>
        @endforelse
    </div>
</div>
    </div>
</div>

<!-- JavaScript to handle image switching -->
<script>
    function changeMainImage(imageSrc) {
        document.querySelector('.main-image img').src = imageSrc;
    }
</script>
@endsection