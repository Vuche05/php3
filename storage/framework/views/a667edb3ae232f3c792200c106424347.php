

<?php $__env->startSection('title', 'Quản lý danh mục'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý danh mục</h5>
        <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-web btn-sm">
            <i class="fas fa-plus"></i> Thêm danh mục mới
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
                        <th>Tên danh mục</th>
                        <th>Hình ảnh</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($category->id); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td>
                                <?php if($category->image && file_exists(public_path('storage/' . $category->image))): ?>
                                    <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" style="max-height: 50px;">
                                <?php else: ?>
                                    <span class="text-muted">Không có hình</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(Str::limit($category->description, 100)); ?></td>
                            <td>
                                <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-sm btn-outline-web me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
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
                            <td colspan="5" class="text-center py-3">Không có danh mục nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/categories/index.blade.php ENDPATH**/ ?>