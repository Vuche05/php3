

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <?php if($product->image): ?>
                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <span class="text-muted">Không có hình ảnh</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-7">
            <h1><?php echo e($product->name); ?></h1>
            <p>
                <span class="badge bg-secondary"><?php echo e($product->category->name); ?></span>
            </p>
            
            <div class="mb-3">
                <?php if($product->discount > 0): ?>
                    <p class="text-decoration-line-through text-muted mb-0">
                        <?php echo e(number_format($product->price, 0, ',', '.')); ?> đ
                    </p>
                    <h3 class="text-danger">
                        <?php echo e(number_format($product->price * (100 - $product->discount) / 100, 0, ',', '.')); ?> đ
                        <span class="badge bg-danger">-<?php echo e($product->discount); ?>%</span>
                    </h3>
                <?php else: ?>
                    <h3><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</h3>
                <?php endif; ?>
            </div>
            
            <p>Số lượng: <?php echo e($product->quantity); ?> sản phẩm có sẵn</p>
            
            <div class="mb-4">
                <h4>Mô tả sản phẩm</h4>
                <p><?php echo e($product->description ?? 'Không có mô tả cho sản phẩm này.'); ?></p>
            </div>
            
            <div class="d-grid gap-2 d-md-block">
                <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-web">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/products/show.blade.php ENDPATH**/ ?>