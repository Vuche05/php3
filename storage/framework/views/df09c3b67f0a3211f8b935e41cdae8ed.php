

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
            color: #ff6200;
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

        .btn-add {
            background-color: #ff6200;
            color: #fff;
        }

        .btn-add:hover {
            background-color: #e55a00;
            color: #fff;
        }

        .address-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .address-card.default {
            border-color: #ff6200;
            background-color: #fff5f0;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-set-default {
            background-color: #ff6200;
            color: #fff;
            border: none;
        }

        .btn-set-default:hover {
            background-color: #e55a00;
        }

        .profile-header-info {
            margin-left: 20px;
        }
    </style>

    <div class="container mt-3">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('profile')); ?>">Thông tin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo e(route('profile.address')); ?>">Địa Chỉ</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('profile.changePassword')); ?>">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 profile-content">
                <div class="profile-header">
                    <?php if(Auth::user()->avatar): ?>
                        <img src="<?php echo e(asset(Auth::user()->avatar)); ?>" alt="Avatar">
                    <?php else: ?>
                        <img src="https://www.gravatar.com/avatar/dfb7d7bb286d54795ab66227e90ff048.jpg?s=80&d=mp&r=g" alt="Avatar">
                    <?php endif; ?>
                    <h2><?php echo e(Auth::user()->username); ?></h2>
                    <div class="profile-header-info">
                        <h2><?php echo e($user->name); ?></h2>
                        <p><?php echo e($user->email); ?></p>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        Thêm địa chỉ mới
                    </button>
                </div>

                <!-- Address List -->
                <div class="address-list">
                    <?php $__empty_1 = true; $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="address-card <?php echo e($address->is_default ? 'default' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1"><strong><?php echo e($address->receiver_name); ?></strong> |
                                        <?php echo e($address->phone); ?></p>
                                    <p class="mb-1"><?php echo e($address->street); ?>, <?php echo e($address->ward); ?>,
                                        <?php echo e($address->district); ?>, <?php echo e($address->province); ?></p>
                                    <?php if($address->is_default): ?>
                                        <span class="badge bg-success">Mặc định</span>
                                    <?php endif; ?>
                                </div>
                                <div class="action-buttons">
                                    <?php if(!$address->is_default): ?>
                                        <form action="<?php echo e(route('profile.setAddress', $address->id)); ?>" method="POST"
                                            class="set-default-form">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-set-default btn-sm">Đặt làm mặc
                                                định</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('profile.deleteAddress', $address->id)); ?>" method="POST"
                                        class="delete-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p>Chưa có địa chỉ nào được thêm.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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

                    <form class="profile-form" action="<?php echo e(route('profile.storeAddress')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="receiver_name" class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['receiver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="receiver_name" name="receiver_name" value="<?php echo e(old('receiver_name')); ?>">
                            <?php $__errorArgs = ['receiver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone"
                                name="phone" value="<?php echo e(old('phone')); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="province" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-control <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="province"
                                name="province">
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <select class="form-control <?php $__errorArgs = ['district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="district"
                                name="district" disabled>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <?php $__errorArgs = ['district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="ward" class="form-label">Xã/Phường</label>
                            <select class="form-control <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ward"
                                name="ward" disabled>
                                <option value="">Chọn xã/phường</option>
                            </select>
                            <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Địa chỉ cụ thể (số nhà, đường)</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="street" name="street" value="<?php echo e(old('street')); ?>">
                            <?php $__errorArgs = ['street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default"
                                value="1">
                            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <button type="submit" class="btn btn-orange">Thêm địa chỉ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        fetch('https://provinces.open-api.vn/api/p/')
            .then(response => response.json())
            .then(data => {
                const provinceSelect = document.getElementById('province');
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.text = province.name;
                    provinceSelect.appendChild(option);
                });
            });

        document.getElementById('province').addEventListener('change', function() {
            const provinceName = this.value;
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (provinceName) {
                fetch(`https://provinces.open-api.vn/api/p/search/?q=${provinceName}`)
                    .then(response => response.json())
                    .then(provinces => {
                        const province = provinces.find(p => p.name === provinceName);
                        if (province) {
                            fetch(`https://provinces.open-api.vn/api/p/${province.code}?depth=2`)
                                .then(response => response.json())
                                .then(data => {
                                    districtSelect.disabled = false;
                                    data.districts.forEach(district => {
                                        const option = document.createElement('option');
                                        option.value = district.name;
                                        option.text = district.name;
                                        districtSelect.appendChild(option);
                                    });
                                });
                        }
                    });
            }
        });

        document.getElementById('district').addEventListener('change', function() {
            const districtName = this.value;
            const provinceName = document.getElementById('province').value;
            const wardSelect = document.getElementById('ward');

            wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
            wardSelect.disabled = true;

            if (districtName) {
                fetch(`https://provinces.open-api.vn/api/p/search/?q=${provinceName}`)
                    .then(response => response.json())
                    .then(provinces => {
                        const province = provinces.find(p => p.name === provinceName);
                        if (province) {
                            fetch(
                                    `https://provinces.open-api.vn/api/d/search/?q=${districtName}&p=${province.code}`
                                    )
                                .then(response => response.json())
                                .then(districts => {
                                    const district = districts.find(d => d.name === districtName);
                                    if (district) {
                                        fetch(
                                                `https://provinces.open-api.vn/api/d/${district.code}?depth=2`
                                                )
                                            .then(response => response.json())
                                            .then(data => {
                                                wardSelect.disabled = false;
                                                data.wards.forEach(ward => {
                                                    const option = document.createElement(
                                                        'option');
                                                    option.value = ward.name;
                                                    option.text = ward.name;
                                                    wardSelect.appendChild(option);
                                                });
                                            });
                                    }
                                });
                        }
                    });
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/profile/addresses.blade.php ENDPATH**/ ?>