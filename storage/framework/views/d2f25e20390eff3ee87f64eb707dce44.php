

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Chi tiết đơn hàng #<?php echo e($order->id); ?></h2>
                <a href="<?php echo e(route('checkout.list')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
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
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã đơn hàng:</strong> #<?php echo e($order->id); ?></p>
                            <p><strong>Ngày đặt:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
                            <p>
                                <strong>Trạng thái:</strong>
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
                            </p>
                            
                            <?php if($order->payment): ?>
                                <p>
                                    <strong>Phương thức thanh toán:</strong>
                                    <?php if($order->payment->payment_method == 'cod'): ?>
                                        Thanh toán khi nhận hàng (COD)
                                    <?php elseif($order->payment->payment_method == 'vnpay'): ?>
                                        VNPay
                                    <?php else: ?>
                                        <?php echo e($order->payment->payment_method); ?>

                                    <?php endif; ?>
                                </p>
                                <p>
                                    <strong>Trạng thái thanh toán:</strong>
                                    <?php switch($order->payment->status):
                                        case ('pending'): ?>
                                            <span class="badge bg-warning">Chờ thanh toán</span>
                                            <?php break; ?>
                                        <?php case ('completed'): ?>
                                            <span class="badge bg-success">Đã thanh toán</span>
                                            <?php break; ?>
                                        <?php case ('failed'): ?>
                                            <span class="badge bg-danger">Thất bại</span>
                                            <?php break; ?>
                                        <?php case ('cancelled'): ?>
                                            <span class="badge bg-danger">Đã hủy</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-secondary"><?php echo e($order->payment->status); ?></span>
                                    <?php endswitch; ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if($order->note): ?>
                                <p><strong>Ghi chú:</strong> <?php echo e($order->note); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Người nhận:</strong> <?php echo e($order->shipping_name); ?></p>
                            <p><strong>Địa chỉ:</strong> <?php echo e($order->shipping_address); ?></p>
                            <p><strong>Số điện thoại:</strong> <?php echo e($order->shipping_phone); ?></p>
                            
                            <?php if($freeShipping): ?>
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-truck me-2"></i> <strong>Miễn phí vận chuyển</strong>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($order->status == 'shipping'): ?>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-shipping-fast me-2"></i> <strong>Thông tin vận chuyển:</strong><br>
                                    Đơn hàng của bạn đang được giao đến địa chỉ đã đăng ký.<br>
                                    Dự kiến giao hàng: <?php echo e(now()->addDays(3)->format('d/m/Y')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Giảm giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($item->product && $item->product->image): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" alt="<?php echo e($item->product->name); ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0">
                                                    <?php if($item->product): ?>
                                                        <a href="<?php echo e(route('products.show', $item->product)); ?>" class="text-decoration-none">
                                                            <?php echo e($item->product->name); ?>

                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sản phẩm không còn tồn tại</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <?php if($item->product && $item->product->sku): ?>
                                                    <small class="text-muted">SKU: <?php echo e($item->product->sku); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e(number_format($item->price, 0, ',', '.')); ?>₫</td>
                                    <td>
                                        <?php if($item->discount > 0): ?>
                                            <?php echo e($item->discount); ?>%
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->quantity); ?></td>
                                    <td class="text-end">
                                        <?php
                                            $itemTotal = $item->price * (1 - $item->discount/100) * $item->quantity;
                                        ?>
                                        <?php echo e(number_format($itemTotal, 0, ',', '.')); ?>₫
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tạm tính:</strong></td>
                                    <td class="text-end"><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>₫</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                    <td class="text-end">
                                        <?php if($freeShipping || ($order->shipping_cost ?? 0) == 0): ?>
                                            <span class="text-success">Miễn phí</span>
                                        <?php else: ?>
                                            <?php echo e(number_format($order->shipping_cost ?? 30000, 0, ',', '.')); ?>₫
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end fw-bold fs-5">
                                        <?php echo e(number_format($order->total_amount + ($order->shipping_cost ?? 0), 0, ',', '.')); ?>₫
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('checkout.list')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                
                <div>
                    <?php if($order->status == 'pending'): ?>
                        <form action="<?php echo e(route('checkout.cancel', $order)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                <i class="fas fa-times"></i> Hủy đơn hàng
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if($order->status == 'completed' || $order->status == 'cancelled'): ?>
                        <a href="<?php echo e(route('checkout.rebuy', $order)); ?>" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Mua lại
                        </a>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .btn, form, nav, footer {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/checkout/show.blade.php ENDPATH**/ ?>