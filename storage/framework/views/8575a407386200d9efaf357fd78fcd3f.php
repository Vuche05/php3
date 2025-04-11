

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Danh sách đơn hàng của bạn</h2>
            
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
            
            <?php if($orders->isEmpty()): ?>
                <div class="alert alert-info">
                    Bạn chưa có đơn hàng nào. <a href="<?php echo e(route('products.index')); ?>">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Phương thức thanh toán</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($order->id); ?></td>
                                <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                                <td><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>₫</td>
                                <td>
                                    <?php switch($order->status):
                                        case ('pending'): ?>
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                            <?php break; ?>
                                        <?php case ('processing'): ?>
                                            <span class="badge bg-info">Đang xử lý</span>
                                            <?php break; ?>
                                        <?php case ('shipping'): ?>
                                            <span class="badge bg-primary">Đang giao hàng</span>
                                            <?php break; ?>
                                        <?php case ('completed'): ?>
                                            <span class="badge bg-success">Hoàn thành</span>
                                            <?php break; ?>
                                        <?php case ('cancelled'): ?>
                                            <span class="badge bg-danger">Đã hủy</span>
                                            <?php break; ?>
                                        <?php case ('failed'): ?>
                                            <span class="badge bg-danger">Thất bại</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-secondary"><?php echo e($order->status); ?></span>
                                    <?php endswitch; ?>
                                </td>
                                <td>
                                    <?php if($order->payment): ?>
                                        <?php if($order->payment->payment_method == 'cod'): ?>
                                            <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                                        <?php elseif($order->payment->payment_method == 'vnpay'): ?>
                                            <span class="badge bg-primary">VNPay</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e($order->payment->payment_method); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chưa thanh toán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('checkout.show', $order)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                    
                                    <?php if($order->status == 'pending'): ?>
                                        <form action="<?php echo e(route('checkout.cancel', $order)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if($order->status == 'completed' || $order->status == 'cancelled'): ?>
                                        <a href="<?php echo e(route('checkout.rebuy', $order)); ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-redo"></i> Mua lại
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/checkout/list.blade.php ENDPATH**/ ?>