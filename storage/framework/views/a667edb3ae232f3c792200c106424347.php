<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Quản lý danh mục</span>
                    <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">Thêm danh mục</a>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Danh mục cha</th>
                                <th>Hình ảnh</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($category->id); ?></td>
                                    <td>
                                        <?php if($category->parent_id): ?>
                                            <span class="ms-<?php echo e(count(explode(' > ', $category->path)) - 1); ?>"><?php echo e($category->name); ?></span>
                                        <?php else: ?>
                                            <strong><?php echo e($category->name); ?></strong>
                                        <?php endif; ?>
                                        
                                        <?php if($category->hasChildren()): ?>
                                            <span class="badge bg-info"><?php echo e($category->children->count()); ?> danh mục con</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($category->parent ? $category->parent->name : 'Không có'); ?></td>
                                    <td>
                                        <?php if($category->image): ?>
                                            <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" width="50">
                                        <?php else: ?>
                                            Không có
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(Str::limit($category->description, 30)); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-sm btn-primary">Sửa</a>
                                        <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/categories/index.blade.php ENDPATH**/ ?>