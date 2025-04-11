@extends('layout.master')

@section('content')
<div class="container mt-3">
    <div class="row">
        <!-- Cột bên trái: Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/profile') }}">Thông tin</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profile.addresses') }}">Địa chỉ của tôi</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                </li>
            </ul>
        </div>

        <!-- Cột bên phải: Form thêm địa chỉ -->
        <div class="col-md-9 col-lg-10 profile-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thêm địa chỉ mới</h5>
                    {{-- <a href="{{ route('profile.addresses') }}" class="btn btn-sm btn-outline-secondary">Quay lại</a> --}}
                </div>
                <div class="card-body">
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.addresses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="receiver_name" class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="province" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                                <!-- Danh sách tỉnh/thành phố sẽ được load bằng AJAX -->
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <select class="form-select" id="district" name="district" required disabled>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ward" class="form-label">Xã/Phường</label>
                            <select class="form-select" id="ward" name="ward" required disabled>
                                <option value="">Chọn xã/phường</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="street" class="form-label">Địa chỉ cụ thể (số nhà, đường)</label>
                            <input type="text" class="form-control" id="street" name="street" value="{{ old('street') }}" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                        </div>
                        
                        <button type="submit" class="btn btn-save">Thêm địa chỉ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch provinces when page loads
    fetch('/api/provinces')
        .then(response => response.json())
        .then(data => {
            const provinceSelect = document.getElementById('province');
            data.forEach(province => {
                let option = document.createElement('option');
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        });
    
    // Handle province change
    document.getElementById('province').addEventListener('change', function() {
        const provinceValue = this.value;
        const districtSelect = document.getElementById('district');
        
        // Reset and enable district dropdown
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        districtSelect.disabled = false;
        
        // Reset ward dropdown
        document.getElementById('ward').innerHTML = '<option value="">Chọn xã/phường</option>';
        document.getElementById('ward').disabled = true;
        
        if (provinceValue) {
            fetch(`/api/districts?province=${encodeURIComponent(provinceValue)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        let option = document.createElement('option');
                        option.value = district.name;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                });
        }
    });
    
    // Handle district change
    document.getElementById('district').addEventListener('change', function() {
        const provinceValue = document.getElementById('province').value;
        const districtValue = this.value;
        const wardSelect = document.getElementById('ward');
        
        // Reset and enable ward dropdown
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        wardSelect.disabled = false;
        
        if (districtValue) {
            fetch(`/api/wards?province=${encodeURIComponent(provinceValue)}&district=${encodeURIComponent(districtValue)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(ward => {
                        let option = document.createElement('option');
                        option.value = ward.name;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                });
        }
    });
});
</script>
@endsection