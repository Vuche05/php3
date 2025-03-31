@extends('layout.master')

@section('content')
<div class="container">
    <h1>Chỉnh sửa Người dùng</h1>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" 
                   value="{{ old('username', $user->username) }}" required>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        @if($user->role !== 'admin')
        <div class="form-group">
            <label for="role">Vai trò</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>  
        @endif

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        {{-- <a href="{{ route('auth.users-list') }}" class="btn btn-secondary">Quay lại</a> --}}
    </form>
</div>
@endsection