@extends('layout.master')

@section('title', 'Sửa Thương Hiệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sửa Thương Hiệu</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('brands.update', $brand->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên Thương Hiệu:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}" required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-web">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection