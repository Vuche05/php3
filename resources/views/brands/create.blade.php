@extends('layout.master')

@section('title', 'Thêm Thương Hiệu Mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Thêm Thương Hiệu Mới</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('brands.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên Thương Hiệu:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-web">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection