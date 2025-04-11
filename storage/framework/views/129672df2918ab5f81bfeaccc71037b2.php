

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Thanh Toán</h1>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    Thông Tin Giao Hàng
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('checkout.process')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="shipping_name">Họ và Tên</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['shipping_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="shipping_name" name="shipping_name" value="<?php echo e(old('shipping_name', Auth::user()->name)); ?>" required>
                            <?php $__errorArgs = ['shipping_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="shipping_address">Địa Chỉ Giao Hàng</label>
                            <textarea class="form-control <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="shipping_address" name="shipping_address" rows="3" required><?php echo e(old('shipping_address')); ?></textarea>
                            <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="shipping_phone">Số Điện Thoại</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['shipping_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="shipping_phone" name="shipping_phone" value="<?php echo e(old('shipping_phone')); ?>" required>
                            <?php $__errorArgs = ['shipping_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="note">Ghi Chú</label>
                            <textarea class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="note" name="note" rows="2"><?php echo e(old('note')); ?></textarea>
                            <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label>Phương Thức Thanh Toán</label>
                            <div class="payment-methods mt-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    </label>
                                    <div class="text-muted small">Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng</div>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                                    <label class="form-check-label" for="vnpay">
                                        <strong>Thanh toán qua VNPay</strong>
                                    </label>
                                    <div class="text-muted small">Thanh toán an toàn với thẻ ATM, Visa, MasterCard qua cổng VNPay</div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Xác Nhận Đặt Hàng</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    Mã Giảm Giá
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('coupon.apply')); ?>" method="POST" class="d-flex">
                        <?php echo csrf_field(); ?>
                        <input type="text" name="coupon_code" class="form-control me-2" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="btn btn-outline-primary">Áp dụng</button>
                    </form>
                    
                    <?php if(session('coupon')): ?>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Mã: <strong><?php echo e(session('coupon')['code']); ?></strong></span>
                                <a href="<?php echo e(route('coupon.remove')); ?>" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    Đơn Hàng Của Bạn
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" width="40" class="me-2">
                                            <div>
                                                <div><?php echo e($item->product->name); ?></div>
                                                <div class="text-muted small">SL: <?php echo e($item->quantity); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <?php if($item->product->discount): ?>
                                            <?php echo e(number_format($item->product->price * (1 - $item->product->discount/100) * $item->quantity)); ?>đ
                                        <?php else: ?>
                                            <?php echo e(number_format($item->product->price * $item->quantity)); ?>đ
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tạm tính</th>
                                <th class="text-end"><?php echo e(number_format($total)); ?>đ</th>
                            </tr>
                            
                            <?php if(session('coupon')): ?>
                            <tr>
                                <td>Giảm giá</td>
                                <td class="text-end text-danger">- <?php echo e(number_format(session('coupon')['discount'])); ?>đ</td>
                            </tr>
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end"><?php echo e(number_format($total - session('coupon')['discount'])); ?>đ</th>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end"><?php echo e(number_format($total)); ?>đ</th>
                            </tr>
                            <?php endif; ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/checkout/index.blade.php ENDPATH**/ ?>