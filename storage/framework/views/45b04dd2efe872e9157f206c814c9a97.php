<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="product-gallery">
                <!-- Main image display -->
                <div class="main-image mb-3">
                    <?php if($product->primaryImage): ?>
                        <img src="<?php echo e(asset('storage/' . $product->primaryImage->image_path)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>">
                    <?php elseif($product->image): ?>
                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <span class="text-muted">Không có hình ảnh</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Thumbnail images -->
                <?php if($product->images && $product->images->count() > 0): ?>
                    <div class="thumbnail-images d-flex flex-wrap">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="thumbnail-item me-2 mb-2" style="width: 80px; cursor: pointer;">
                                <img 
                                    src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                    class="img-thumbnail <?php echo e($image->is_primary ? 'border-primary' : ''); ?>"
                                    alt="Thumbnail" 
                                    onclick="changeMainImage('<?php echo e(asset('storage/' . $image->image_path)); ?>')"
                                >
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
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
        <!-- Sản phẩm liên quan -->
<div class="related-products mt-5">
    <h3>Sản phẩm liên quan</h3>
    <div class="row mt-4">
        <?php $__empty_1 = true; $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="product-image-container" style="height: 200px; overflow: hidden;">
                        <?php if($relatedProduct->primaryImage): ?>
                            <img src="<?php echo e(asset('storage/' . $relatedProduct->primaryImage->image_path)); ?>" class="card-img-top" alt="<?php echo e($relatedProduct->name); ?>" style="object-fit: cover; height: 100%;">
                        <?php elseif($relatedProduct->image): ?>
                            <img src="<?php echo e(asset('storage/' . $relatedProduct->image)); ?>" class="card-img-top" alt="<?php echo e($relatedProduct->name); ?>" style="object-fit: cover; height: 100%;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <span class="text-muted">Không có hình ảnh</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($relatedProduct->discount > 0): ?>
                            <div class="position-absolute top-0 end-0 bg-danger text-white p-1">
                                -<?php echo e($relatedProduct->discount); ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($relatedProduct->name); ?></h5>
                        
                        <div class="mt-auto">
                            <?php if($relatedProduct->discount > 0): ?>
                                <p class="text-decoration-line-through text-muted mb-0">
                                    <?php echo e(number_format($relatedProduct->price, 0, ',', '.')); ?> đ
                                </p>
                                <p class="text-danger fw-bold mb-2">
                                    <?php echo e(number_format($relatedProduct->price * (100 - $relatedProduct->discount) / 100, 0, ',', '.')); ?> đ
                                </p>
                            <?php else: ?>
                                <p class="fw-bold mb-2"><?php echo e(number_format($relatedProduct->price, 0, ',', '.')); ?> đ</p>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <p class="text-muted">Không có sản phẩm liên quan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
    </div>
</div>

<!-- JavaScript to handle image switching -->
<script>
    function changeMainImage(imageSrc) {
        document.querySelector('.main-image img').src = imageSrc;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/products/show.blade.php ENDPATH**/ ?>