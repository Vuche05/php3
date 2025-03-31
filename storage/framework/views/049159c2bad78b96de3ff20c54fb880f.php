

<?php $__env->startSection('title', 'Thêm Thương Hiệu Mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Thêm Thương Hiệu Mới</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('brands.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên Thương Hiệu:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-web">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="<?php echo e(route('brands.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/brands/create.blade.php ENDPATH**/ ?>