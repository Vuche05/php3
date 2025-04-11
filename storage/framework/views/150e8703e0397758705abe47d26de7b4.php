<!-- resources/views/cart/index.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Giỏ Hàng</h1>

    <?php if($cartItems->count() > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" width="50">
                        <?php echo e($item->product->name); ?>

                    </td>
                    <td>
                        <?php if($item->product->discount): ?>
                            <del><?php echo e(number_format($item->product->price)); ?>đ</del>
                            <?php echo e(number_format($item->product->price * (1 - $item->product->discount/100))); ?>đ
                        <?php else: ?>
                            <?php echo e(number_format($item->product->price)); ?>đ
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="<?php echo e(route('cart.update', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="1" max="<?php echo e($item->product->quantity); ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                        </form>
                    </td>
                    <td>
                        <?php echo e(number_format($item->total_price)); ?>đ
                    </td>
                    <td>
                        <form action="<?php echo e(route('cart.remove', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Tổng Cộng:</td>
                    <td><?php echo e(number_format($total)); ?>đ</td>
                    <td>
                        <form action="<?php echo e(route('cart.clear')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-warning">Xóa Giỏ Hàng</button>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
        <a href="<?php echo e(route('checkout.index')); ?>" class="btn btn-success">Thanh Toán</a>
    <?php else: ?>
        <p>Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/cart/index.blade.php ENDPATH**/ ?>