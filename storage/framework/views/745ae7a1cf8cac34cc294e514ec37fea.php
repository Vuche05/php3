

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết đơn hàng #<?php echo e($order->order_number); ?></h5>
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-sm btn-light">Quay lại</a>
        </div>
        
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
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Thông tin đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="ps-0">Mã đơn hàng:</th>
                                    <td><?php echo e($order->order_number); ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Ngày đặt hàng:</th>
                                    <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Trạng thái:</th>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'shipping' => 'bg-primary',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                            ][$order->status] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo e($statusClass); ?>"><?php echo e($order->status_name); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Phương thức thanh toán:</th>
                                    <td><?php echo e($order->payment_method_name); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Thông tin giao hàng</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="ps-0">Họ tên:</th>
                                    <td><?php echo e($order->shipping_name); ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Địa chỉ:</th>
                                    <td><?php echo e($order->shipping_address); ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Số điện thoại:</th>
                                    <td><?php echo e($order->shipping_phone); ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Email:</th>
                                    <td><?php echo e($order->shipping_email); ?></td>
                                </tr>
                                <?php if($order->notes): ?>
                                <tr>
                                    <th class="ps-0">Ghi chú:</th>
                                    <td><?php echo e($order->notes); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Chi tiết sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($item->product && $item->product->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" alt="<?php echo e($item->product->name); ?>" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light me-3" style="width: 50px; height: 50px;"></div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($item->product ? $item->product->name : 'Sản phẩm không còn tồn tại'); ?></h6>
                                                    <?php if($item->discount > 0): ?>
                                                        <small class="text-muted">Giảm giá: <?php echo e($item->discount); ?>%</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo e(number_format($item->final_price, 0, ',', '.')); ?>đ</td>
                                        <td class="text-center"><?php echo e($item->quantity); ?></td>
                                        <td class="text-end"><?php echo e(number_format($item->total, 0, ',', '.')); ?>đ</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tạm tính:</td>
                                    <td class="text-end"><?php echo e(number_format($order->total_amount + $order->discount_amount, 0, ',', '.')); ?>đ</td>
                                </tr>
                                <?php if($order->discount_amount > 0): ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Giảm giá:</td>
                                    <td class="text-end text-danger">-<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?>đ</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5"><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php if($order->status === 'pending'): ?>
                <div class="text-center">
                    <form action="<?php echo e(route('orders.cancel', $order)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                    </form>
                </div>
            <?php elseif($order->payment_method === 'bank_transfer' && $order->status !== 'cancelled'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0">Thông tin chuyển khoản</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">Ngân hàng: VCB - Ngân hàng TMCP Ngoại thương Việt Nam</p>
                        <p class="mb-1">Số tài khoản: 1234567890</p>
                        <p class="mb-1">Chủ tài khoản: CÔNG TY TNHH ABC</p>
                        <p class="mb-0">Nội dung: Thanh toán đơn hàng <?php echo e($order->order_number); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/orders/show.blade.php ENDPATH**/ ?>