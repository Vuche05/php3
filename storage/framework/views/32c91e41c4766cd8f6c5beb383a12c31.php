

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa mã giảm giá: <?php echo e($coupon->code); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('coupon.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('coupon.update', $coupon)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Mã giảm giá <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" name="code" value="<?php echo e(old('code', $coupon->code)); ?>" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="generate-code">Tạo mã mới</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Loại giảm giá <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="fixed" <?php echo e(old('type', $coupon->type) == 'fixed' ? 'selected' : ''); ?>>Cố định (VNĐ)</option>
                                        <option value="percent" <?php echo e(old('type', $coupon->type) == 'percent' ? 'selected' : ''); ?>>Phần trăm (%)</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="value">Giá trị <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="value" name="value" value="<?php echo e(old('value', $coupon->value)); ?>" min="0" step="0.01" required>
                                    <small class="form-text text-muted" id="value-help">Nhập số tiền hoặc % giảm giá</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="min_order_amount">Giá trị đơn hàng tối thiểu</label>
                                    <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" value="<?php echo e(old('min_order_amount', $coupon->min_order_amount)); ?>" min="0">
                                    <small class="form-text text-muted">Bỏ trống nếu không cần mức tối thiểu</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="max_discount_amount">Giảm giá tối đa</label>
                                    <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" value="<?php echo e(old('max_discount_amount', $coupon->max_discount_amount)); ?>" min="0">
                                    <small class="form-text text-muted">Chỉ áp dụng cho loại phần trăm. Bỏ trống nếu không giới hạn</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="starts_at">Ngày bắt đầu</label>
                                    <input type="datetime-local" class="form-control" id="starts_at" name="starts_at" 
                                        value="<?php echo e(old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '')); ?>">
                                    <small class="form-text text-muted">Bỏ trống nếu có hiệu lực ngay</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="expires_at">Ngày hết hạn</label>
                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" 
                                        value="<?php echo e(old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '')); ?>">
                                    <small class="form-text text-muted">Bỏ trống nếu không giới hạn thời gian</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="usage_limit">Giới hạn sử dụng</label>
                                    <input type="number" class="form-control" id="usage_limit" name="usage_limit" value="<?php echo e(old('usage_limit', $coupon->usage_limit)); ?>" min="1">
                                    <small class="form-text text-muted">Bỏ trống nếu không giới hạn số lần sử dụng</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $coupon->description)); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', $coupon->is_active) ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="is_active">Hoạt động</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <p class="mb-1"><strong>Đã sử dụng:</strong> <?php echo e($coupon->used_count); ?> lần</p>
                                    <p class="mb-0"><strong>Trạng thái:</strong> 
                                        <?php if($coupon->isValid()): ?>
                                            <span class="badge badge-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Không hợp lệ</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Change value help text based on coupon type
        const typeSelect = document.getElementById('type');
        const valueHelp = document.getElementById('value-help');
        
        typeSelect.addEventListener('change', function() {
            if (this.value === 'fixed') {
                valueHelp.textContent = 'Nhập số tiền giảm giá (VNĐ)';
            } else {
                valueHelp.textContent = 'Nhập phần trăm giảm giá (0-100)';
            }
        });
        
        // Generate code button
        const generateBtn = document.getElementById('generate-code');
        const codeInput = document.getElementById('code');
        
        generateBtn.addEventListener('click', function() {
            fetch('<?php echo e(route("coupon.generate-code")); ?>')
                .then(response => response.json())
                .then(data => {
                    codeInput.value = data.code;
                })
                .catch(error => {
                    console.error('Error generating code:', error);
                });
        });
        
        // Trigger initial type change event
        typeSelect.dispatchEvent(new Event('change'));
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/coupon/edit.blade.php ENDPATH**/ ?>