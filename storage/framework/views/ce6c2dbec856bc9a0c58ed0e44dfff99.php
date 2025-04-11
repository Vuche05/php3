

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý mã giảm giá</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('coupon.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mã giảm giá
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Đơn hàng tối thiểu</th>
                                    <th>Giảm tối đa</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Giới hạn sử dụng</th>
                                    <th>Đã dùng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($coupon->code); ?></td>
                                    <td><?php echo e($coupon->type === 'fixed' ? 'Cố định' : 'Phần trăm'); ?></td>
                                    <td><?php echo e($coupon->formatted_value); ?></td>
                                    <td><?php echo e($coupon->min_order_amount ? number_format($coupon->min_order_amount, 0, ',', '.') . 'đ' : 'Không'); ?></td>
                                    <td><?php echo e($coupon->max_discount_amount ? number_format($coupon->max_discount_amount, 0, ',', '.') . 'đ' : 'Không'); ?></td>
                                    <td><?php echo e($coupon->starts_at ? $coupon->starts_at->format('d/m/Y') : 'Không giới hạn'); ?></td>
                                    <td><?php echo e($coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn'); ?></td>
                                    <td><?php echo e($coupon->usage_limit ?: 'Không giới hạn'); ?></td>
                                    <td><?php echo e($coupon->used_count); ?></td>
                                    <td>
                                        <?php if($coupon->isValid()): ?>
                                            <span class="badge badge-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Không hợp lệ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('coupons.edit', $coupon)); ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('coupons.destroy', $coupon)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="11" class="text-center">Không có mã giảm giá nào</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <?php echo e($coupons->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/coupons/index.blade.php ENDPATH**/ ?>