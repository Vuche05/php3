<?php $__env->startSection('title', 'Quản lý sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý sản phẩm</h5>
        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-web btn-sm">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Hình ảnh</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($product->id); ?></td>
                            <td><?php echo e($product->name); ?></td>
                            <td><?php echo e($product->category->name); ?></td>
                            <td><?php echo e($product->brand->name); ?></td>
                            <td><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</td>
                            <td><?php echo e($product->quantity); ?></td>
                            <td>
                                <?php if($product->primaryImage): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>" style="max-height: 50px;">
                                <?php elseif($product->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" style="max-height: 50px;">
                                <?php else: ?>
                                    <span class="text-muted">Không có hình</span>
                                <?php endif; ?>
                                
                                <?php if($product->images && $product->images->where('is_primary', false)->count() > 0): ?>
                                    <span class="badge bg-info ms-1">+<?php echo e($product->images->where('is_primary', false)->count()); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-sm btn-outline-web me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-3">Không có sản phẩm nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/products/index.blade.php ENDPATH**/ ?>