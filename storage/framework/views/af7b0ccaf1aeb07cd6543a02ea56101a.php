

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
        </div>
        <h1>Đặt Hàng Thành Công!</h1>
        <p class="lead">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đang được xử lý.</p>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Chi tiết đơn hàng #<?php echo e($order->id); ?></span>
                <span class="badge bg-info"><?php echo e(ucfirst($order->status)); ?></span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin giao hàng</h5>
                    <p>
                        <strong>Người nhận:</strong> <?php echo e($order->shipping_name); ?><br>
                        <strong>Địa chỉ:</strong> <?php echo e($order->shipping_address); ?><br>
                        <strong>Số điện thoại:</strong> <?php echo e($order->shipping_phone); ?>

                    </p>
                    
                    <?php if($order->note): ?>
                        <p><strong>Ghi chú:</strong> <?php echo e($order->note); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h5>Thông tin thanh toán</h5>
                    <p>
                        <strong>Phương thức:</strong> 
                        <?php if($order->payment->payment_method == 'cod'): ?>
                            Thanh toán khi nhận hàng (COD)
                        <?php else: ?>
                            Thanh toán qua VNPay
                        <?php endif; ?>
                        <br>
                        <strong>Trạng thái:</strong> 
                        <?php if($order->payment->status == 'completed'): ?>
                            <span class="text-success">Đã thanh toán</span>
                        <?php elseif($order->payment->status == 'pending'): ?>
                            <span class="text-warning">Chờ thanh toán</span>
                        <?php else: ?>
                            <span class="text-danger">Thất bại</span>
                        <?php endif; ?>
                        <br>
                        <?php if($order->discount_amount > 0): ?>
                            <div class="row mb-2">
                                <div class="col-6">Mã giảm giá:</div>
                                <div class="col-6 text-end"><?php echo e($order->coupon->code ?? 'N/A'); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Giảm giá:</div>
                                <div class="col-6 text-end text-danger">- <?php echo e(number_format($order->discount_amount)); ?>đ</div>
                            </div>
                        <?php endif; ?>
                        <strong>Tổng tiền:</strong> <?php echo e(number_format($order->total_amount)); ?>đ
                    </p>
                </div>
            </div>
            
            <h5>Sản phẩm đã đặt</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" width="40" class="me-2">
                                    <?php echo e($item->product->name); ?>

                                </div>
                            </td>
                            <td>
                                <?php if($item->discount): ?>
                                    <del><?php echo e(number_format($item->price)); ?>đ</del>
                                    <?php echo e(number_format($item->price * (1 - $item->discount/100))); ?>đ
                                <?php else: ?>
                                    <?php echo e(number_format($item->price)); ?>đ
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td class="text-end">
                                <?php if($item->discount): ?>
                                    <?php echo e(number_format($item->price * (1 - $item->discount/100) * $item->quantity)); ?>đ
                                <?php else: ?>
                                    <?php echo e(number_format($item->price * $item->quantity)); ?>đ
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Tổng cộng</th>
                        <th class="text-end"><?php echo e(number_format($order->total_amount)); ?>đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="text-center">
        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="<?php echo e(route('checkout.list')); ?>" class="btn btn-outline-secondary">Xem tất cả đơn hàng</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/checkout/success.blade.php ENDPATH**/ ?>