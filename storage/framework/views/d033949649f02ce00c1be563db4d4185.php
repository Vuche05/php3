<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Xác Nhận Mã OTP</div>
                <div class="card-body">
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

                    <form method="POST" action="<?php echo e(route('password.validate-otp')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="otp">Mã OTP</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="otp" 
                                   name="otp" 
                                   required 
                                   maxlength="6" 
                                   pattern="\d{6}" 
                                   placeholder="Nhập 6 chữ số"
                                   autofocus>
                            <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                Xác Nhận
                            </button>
                            <a href="<?php echo e(route('password.forgot')); ?>" class="btn btn-link">
                                Gửi lại mã OTP
                            </a>
                        </div>
                    </form>

                    <div class="mt-3 text-muted">
                        <small>
                            * Mã OTP có hiệu lực trong 15 phút
                            * Mã chỉ được sử dụng một lần
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>