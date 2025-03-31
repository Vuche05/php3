@extends('layout.master')

@section('content')
<div class="container">
    <h1>Quản lý Người dùng</h1>

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

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Username</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="{{ $user->role == 'admin' ? 'text-danger' : 'text-primary' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    @if(Auth::user()->isAdmin() && $user->id !== Auth::id())
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Sửa</a>
                        @if($user->role !== 'admin')
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</button>
                        </form>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }} {{-- Phân trang --}}
</div>
@endsection