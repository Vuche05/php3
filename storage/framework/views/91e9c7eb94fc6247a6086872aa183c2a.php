<?php $__env->startSection('content'); ?>
    <style>
        .sidebar {
            background-color: #fff;
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 10px 20px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #f0f2f5;
            color: #326E51;
        }

        .profile-content {
            padding: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .profile-form .form-label {
            font-weight: 500;
        }

        .profile-form .form-control {
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .btn-save {
            background-color: #326E51;
            border-color: #326E51;
            color: #fff;
        }

        .btn-save:hover {
            background-color: #e55a00;
            border-color: #e55a00;
        }
        
        .address-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .address-type {
            background-color: #f0f2f5;
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .default-badge {
            background-color: #326E51;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            margin-left: 5px;
        }
        
        .address-actions {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        
        .address-tabs {
            margin-bottom: 20px;
        }
    </style>
    <div class="container mt-3">
        <div class="row">
            <!-- Cột bên trái: Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo e(url('/profile')); ?>">Thông tin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(url('/profile/address')); ?>">Địa chỉ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(url('/profile/changePassword')); ?>">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <!-- Cột bên phải: Thông tin cá nhân -->
            <div class="col-md-9 col-lg-10 profile-content">
                <div class="profile-header">
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset($user->avatar)); ?>" alt="Avatar">
                    <?php else: ?>
                        <img src="https://www.gravatar.com/avatar/dfb7d7bb286d54795ab66227e90ff048.jpg?s=80&d=mp&r=g" alt="Avatar">
                    <?php endif; ?>
                    <h2><?php echo e(Auth::user()->username); ?></h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin cá nhân</h5>

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
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form class="profile-form" action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo e(Auth::user()->username); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo e(old('email', Auth::user()->email)); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="<?php echo e(old('fullname', $user->fullname)); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?php echo e(old('phone', $user->phone ?? '')); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Ảnh đại diện:</label>
                                <input type="file" class="form-control" id="avatar" name="avatar">
                                <?php if($user->avatar): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo e(asset($user->avatar)); ?>" alt="Avatar"
                                            class="img-thumbnail rounded-circle" width="150" height="150">
                                        <button type="button" class="btn btn-danger btn-sm ml-2"
                                            onclick="deleteAvatar()">Xóa ảnh</button>
                                    </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <button type="submit" class="btn btn-save">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>

    <script>
        function deleteAvatar() {
            if (confirm('Bạn có chắc muốn xóa ảnh đại diện không?')) {
                fetch('<?php echo e(route('profile.delete.avatar')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Tải lại trang để cập nhật giao diện
                    } else {
                        alert('Xóa ảnh thất bại');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi');
                });
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/profile.blade.php ENDPATH**/ ?>