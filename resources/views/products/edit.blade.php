@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa sản phẩm</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Thương hiệu <span class="text-danger">*</span></label>
                                    <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Giá <span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" min="1" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity) }}" required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount">Giảm giá (%)</label>
                                    <input type="number" name="discount" id="discount" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $product->discount) }}">
                                    @error('discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>

                        <!-- Primary Image -->
                        <div class="form-group mb-3">
                            <label for="primary_image">Hình ảnh chính</label>
                            <div class="mb-2">
                                @if ($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" style="max-height: 100px;">
                                @elseif ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height: 100px;">
                                @else
                                    <span class="text-muted">Chưa có hình ảnh chính</span>
                                @endif
                            </div>
                            <input type="file" name="primary_image" id="primary_image" class="form-control @error('primary_image') is-invalid @enderror">
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh chính</small>
                            @error('primary_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Additional Images -->
                        <div class="form-group mb-3">
                            <label for="additional_images">Thêm hình ảnh phụ</label>
                            <input type="file" name="additional_images[]" id="additional_images" class="form-control @error('additional_images') is-invalid @enderror" multiple>
                            <small class="form-text text-muted">Có thể chọn nhiều hình ảnh</small>
                            @error('additional_images')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @error('additional_images.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Existing Additional Images -->
                        @if ($product->images && $product->images->where('is_primary', false)->count() > 0)
                            <div class="form-group mb-3">
                                <label>Hình ảnh phụ hiện tại</label>
                                <div class="row">
                                    @foreach ($product->images->where('is_primary', false) as $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="Product image" style="height: 100px; object-fit: cover;">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}" id="remove_image_{{ $image->id }}">
                                                        <label class="form-check-label" for="remove_image_{{ $image->id }}">
                                                            Xóa
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection