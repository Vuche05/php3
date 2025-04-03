@extends('layout.master')

@section('title', 'Đăng nhập')

@section('content')
<style>
    .btn-facebook {
            background-color: #3b5998;
            color: white;
        }

        .btn-facebook:hover {
            background-color: #ffffff;
            color: #3b5998;
            border: 1px solid #3b5998;
        }

        .btn-google {
            background-color: #db4437;
            color: white;
        }

        .btn-google:hover {
            background-color: #ffffff;
            color: #db4437;
            border: 1px solid #db4437;
        }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-web text-white">
                    <h4 class="mb-0">Đăng nhập</h4>
                </div>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <div class="mb-3 text-end">
                            <a href="{{ route('password.forgot') }}" class="text-decoration-none">Quên mật khẩu?</a>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-web">Đăng nhập</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Hoặc đăng nhập bằng:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/auth/facebook" class="btn btn-facebook social-btn w-100">
                                <i class="bi bi-facebook me-2"></i> Facebook
                            </a>
                            <a href="/auth/google" class="btn btn-google social-btn w-100">
                                <i class="bi bi-google me-2"></i> Google
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p>Chưa có tài khoản? <a href="{{ route('register') }}" class="text-web">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection