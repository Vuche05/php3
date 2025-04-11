

<?php $__env->startSection('content'); ?>
<style>
    .address-form {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .address-form .form-label {
        font-weight: 500;
    }
    
    .btn-submit {
        background-color: #326E51;
        border-color: #326E51;
        padding: 8px 16px;
    }
    
    .btn-submit:hover {
        background-color: #e55a00;
        border-color: #e55a00;
    }
    
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="address-form">
                <div class="form-header">
                    <h4>Thêm địa chỉ mới</h4>
                    <a href="<?php echo e(route('profile.addresses')); ?>" class="close-btn">&times;</a>
                </div>
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('profile.addresses.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" value="<?php echo e(old('recipient_name')); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="city" class="form-label">Tỉnh/Thành phố</label>
                        <select class="form-select" id="city" name="city" required>
                            <option selected disabled>Chọn tỉnh/thành phố</option>
                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($city); ?>" <?php echo e(old('city') == $city ? 'selected' : ''); ?>>
                                    <?php echo e($city); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label for="district" class="form-label">Quận/Huyện</label>
                        <select class="form-select" id="district" name="district" required>
                            <option selected disabled>Chọn quận/huyện</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ward" class="form-label">Xã/Phường</label>
                        <select class="form-select" id="ward" name="ward" required>
                            <option selected disabled>Chọn xã/phường</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Địa chỉ cụ thể (số nhà, đường)</label>
                        <input type="text" class="form-control" id="address_line" name="address_line" value="<?php echo e(old('address_line')); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_type" class="form-label">Loại địa chỉ</label>
                        <select class="form-select" id="address_type" name="address_type">
                            <option value="Home" <?php echo e(old('address_type') == 'Home' ? 'selected' : ''); ?>>Nhà riêng</option>
                            <option value="Office" <?php echo e(old('address_type') == 'Office' ? 'selected' : ''); ?>>Văn phòng</option>
                            <option value="Other" <?php echo e(old('address_type') == 'Other' ? 'selected' : ''); ?>>Khác</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"><?php echo e(old('notes')); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" <?php echo e(old('is_default') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_default">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-submit text-white">Thêm địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get references to the dropdown elements
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Add event listener for city change
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        
        // Clear district and ward dropdowns
        districtSelect.innerHTML = '<option selected disabled>Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option selected disabled>Chọn xã/phường</option>';
        
        if (cityId) {
            // Fetch districts for the selected city
            fetch(`/api/districts?city_id=${cityId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching districts:', error));
        }
    });
    
    // Add event listener for district change
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        
        // Clear ward dropdown
        wardSelect.innerHTML = '<option selected disabled>Chọn xã/phường</option>';
        
        if (districtId) {
            // Fetch wards for the selected district
            fetch(`/api/wards?district_id=${districtId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.id;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching wards:', error));
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/profile/addresses/create.blade.php ENDPATH**/ ?>