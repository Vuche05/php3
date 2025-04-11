<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// // Resource Routes
Route::resource('categories', CategoryController::class);
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::get('/categories/{category}', [CategoryController::class,'show'])->name('category.show');
Route::resource('products', ProductController::class);
Route::resource('brands', BrandController::class);

// Product Routes for Users
Route::get('/products-for-user', [ProductController::class, 'userProducts'])->name('products.user');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

// Categories (Chỉ Admin có quyền quản lý)
Route::middleware(['auth', 'admin'])->group(function () {
    // Thay đổi từ một route đơn lẻ thành resource route
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::get('/users', [UserController::class, 'listUsers'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroyUser'])->name('users.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::post('/orders/{order}/notification', [OrderController::class, 'sendNotification'])->name('orders.send_notification');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/checkout/list', [CheckoutController::class, 'list'])->name('checkout.list');
    Route::get('/checkout/show/{order}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::patch('/checkout/{order}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/orders/{order}/rebuy', [CheckoutController::class, 'rebuy'])->name('checkout.rebuy');
    
    Route::get('/checkout/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/checkout/update-shipping', [CheckoutController::class, 'updateShipping'])->name('checkout.update_shipping');
    Route::post('/checkout/validate-discount', [CheckoutController::class, 'validateDiscount'])->name('checkout.validate_discount');
});

Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/delete-avatar', [UserController::class, 'deleteAvatar'])->name('profile.delete.avatar');
Route::get('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.changePassword');
Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');

Route::get('/profile/address', [UserController::class, 'address'])->name('profile.address');
Route::post('/profile/address/store', [UserController::class, 'storeAddress'])->name('profile.storeAddress');
Route::post('/profile/address/set-default/{id}', [UserController::class, 'setAddress'])->name('profile.setAddress');
Route::delete('/profile/address/delete/{id}', [UserController::class, 'deleteAddress'])->name('profile.deleteAddress');



// API lấy dữ liệu tỉnh, quận, phường
Route::middleware(['auth'])->group(function () {
    Route::get('/api/provinces', [AddressController::class, 'getProvinces']);
    Route::get('/api/districts', [AddressController::class, 'getDistricts']);
    Route::get('/api/wards', [AddressController::class, 'getWards']);
});

//quen mat khau
Route::get('/quen-mat-khau', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/quen-mat-khau', [UserController::class, 'sendResetLink'])->name('password.send-link');
Route::get('/xac-nhan-otp', [UserController::class, 'verifyOtp'])->name('password.verify-otp');
Route::post('/xac-nhan-otp', [UserController::class, 'validateOtp'])->name('password.validate-otp');
Route::get('/dat-lai-mat-khau', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/dat-lai-mat-khau', [UserController::class, 'resetPassword'])->name('password.update');

//dang nhap voi google va facebook
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [UserController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [UserController::class, 'handleFacebookCallback']);

Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply'); 
Route::get('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
Route::get('/coupon', [CouponController::class, 'index'])->name('coupon.index');
Route::get('/coupon/create', [CouponController::class, 'create'])->name('coupon.create');
Route::post('/coupon', [CouponController::class, 'store'])->name('coupon.store');
Route::get('/coupon/generate-code', [CouponController::class, 'generateCode'])->name('coupon.generate-code');
Route::get('/coupon/{coupon}/edit', [CouponController::class, 'edit'])->name('coupon.edit');
Route::put('/coupon/{coupon}', [CouponController::class, 'update'])->name('coupon.update');
Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->name('coupon.destroy'); 

// In routes/web.php, inside the middleware(['auth', 'admin']) group, add:
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/orders', [CheckoutController::class, 'adminIndex'])->name('orders.index');
//     Route::get('/orders/{order}', [CheckoutController::class, 'adminShow'])->name('orders.show');
//     Route::put('/orders/{order}/status', [CheckoutController::class, 'updateStatus'])->name('orders.update.status');
//     Route::get('/orders/{order}/edit', [CheckoutController::class, 'adminEdit'])->name('orders.edit');
// });

// Route::get('/info/{id}', function(string $id) {
//     $sinhviens = array(
//         '1' => array (
//             'name' => 'vu che',
//             'age' => 30,
//             'email' => 'vuche@gmail.com',
//         ),
//         '2' => array (
//             'name' => 'quang vu',
//             'age' => 18,
//             'email' => 'quangvu@gmail.com',
//         ),
//     );
//     if (!empty($sinhviens[$id])) {
//         $info = $sinhviens[$id];
//         return view('info_detail', compact('info'));
//     }
//     else {
//         abort(404);
//     }
// });

// Route::get('/', function () {
//     $data = [
//         'title_left' => 'Giới Thiệu',
//         'title' => 'Trang chủ của tôi',
//         'intro_title' => 'Xin chào, tôi là Vũ Chế',
//         'text_slider' => 'Tôi là Developer',
//         'name' => 'Vũ Chế',
//         'job' => 'Developer',
//         'email' => 'vuche@gmail.com',
//         'phone' => '0909090909',
//         'image' => 'img/cv.jpg',
//         'about_me' => [

//             'Tôi là một lập trình viên web và mobile, tôi đã thành công với nhiều dự án quan trọng. Tôi có thể hỗ trợ bạn trong việc tư vấn web, mobile, và lập trình PHP.',
//             'Tôi cũng có thể hỗ trợ bạn trong việc tư vấn về PHP, Laravel, ReactJS, Node.js, và các framework khác.',
//         ],
//         'skills' => [
//             ['name' => 'HTML', 'percentage' => 85],
//             ['name' => 'CSS3', 'percentage' => 75],
//             ['name' => 'PHP', 'percentage' => 50],
//             ['name' => 'JAVASCRIPT', 'percentage' => 90]
//         ],
            
       
//         'title_a' => 'Dịch Vụ',
//         'subtitle_a' => 'Dịch vụ số 1 Việt Nam',
//         's_title' => 'Thiết Kế Web',
//         's_description' => 'Chúng tôi cung cấp dịch vụ thiết kế website chuyên nghiệp, tạo ra giao diện hấp dẫn và dễ sử dụng. Đội ngũ thiết kế của chúng tôi sẽ xây dựng website phù hợp với thương hiệu và nhu cầu kinh doanh của bạn.',
//         's_title2' => 'Phát Triển Web',
//         's_description2' => ' Dịch vụ phát triển web của chúng tôi bao gồm lập trình back-end, tích hợp cơ sở dữ liệu và tối ưu hóa hiệu suất. Chúng tôi sử dụng các công nghệ hiện đại để đảm bảo website của bạn hoạt động mượt mà và an toàn.',
//         's_title3' => 'Nhiếp Ảnh',
//         's_description3' => 'Chúng tôi cung cấp dịch vụ chụp ảnh chuyên nghiệp cho sản phẩm, doanh nghiệp và sự kiện. Hình ảnh chất lượng cao sẽ giúp tăng tính hấp dẫn và độ tin cậy cho thương hiệu của bạn.',
//         's_title4' => 'Thiết Kế Phản Hồi',
//         's_description4' => ' Thiết kế web đáp ứng đảm bảo website của bạn hiển thị tốt trên mọi thiết bị, từ máy tính để bàn đến điện thoại di động. Chúng tôi tạo ra trải nghiệm người dùng tuyệt vời trên mọi kích thước màn hình.',
//         's_title5' => 'Thiết Kế Đồ Họa',
//         's_description5' => 'Dịch vụ thiết kế đồ họa của chúng tôi bao gồm thiết kế logo, banner, ấn phẩm quảng cáo và các tài liệu marketing. Chúng tôi tạo ra các thiết kế độc đáo và bắt mắt để thu hút khách hàng.',
//         's_title6' => 'Dịch Vụ Tiếp Thị',
//         's_description6' => ' Chúng tôi cung cấp các dịch vụ marketing toàn diện để giúp doanh nghiệp của bạn tiếp cận khách hàng tiềm năng và tăng doanh số. Các chiến lược marketing của chúng tôi được thiết kế riêng phù hợp với mục tiêu kinh doanh của bạn.',

//         'counter' => '100',
//         'counter_text' => 'Công Trình Đã Hoàn Thành',
//         'counter2' => '100',
//         'counter_text2' => 'Số Năm Kinh Nghiệm',
//         'counter3' => '100',
//         'counter_text3' => 'Tổng Số Khách Hàng',
//         'counter4' => '100',
//         'counter_text4' => 'Giải Thưởng Đã Đạt Được',

//         'title_b' => 'Danh mục đầu tư',
//         'subtitle_b' => 'Các danh mục đa dạng',
//         'w_title' => 'Các Chức năng',
//         'w_ctegory' => 'Web',
//         'w_title2' => 'Hành Trình Khám Phá',
//         'w_ctegory2' => 'Web',
//         'w_title3' => 'Kết nối cuộc sống',
//         'w_ctegory3' => 'Web',
//         'w_title4' => 'Xưởng thiết kế Lena Mado',
//         'w_ctegory4' => 'Web',
//         'w_title5' => 'Xưởng thiết kế',
//         'w_ctegory5' => 'Web',
//         'w_title6' => 'Khám Phá Hành Trình',
//         'w_ctegory6' => 'Web',

//         'author' => 'Vũ Chế',
//         'description_lead' => 'Tâm hồn tự do luôn khát khao khám phá những chân trời mới. Cuộc sống là một hành trình đầy màu sắc với những trải nghiệm độc đáo và ý nghĩa.',
//         'author2' => 'Long Vũ',
//         'description_lead2' => 'Tâm hồn tự do luôn khát khao khám phá những chân trời mới. Cuộc sống là một hành trình đầy màu sắc với những trải nghiệm độc đáo và ý nghĩa.',

//         'blog1' => 'Bài Viết',
//         'sub_blog' => 'Các bài viết cơ bản',
//         'category1' => 'Thể Thao',
//         'card_title' => 'Xem thêm ý tưởng về Du lịch',
//         'sub_category' => 'Có hình ảnh đôi chân đặt trên xe hơi nhìn ra cánh đồng hoa vàng và bầu trời.',
//         'category2' => 'Thức Ăn',
//         'card_title2' => 'Xem thêm nhiều món ăn',
//         'sub_category2' => 'Có hình ảnh một bát thức ăn lành mạnh với nhiều rau và nguyên liệu tươi',
//         'category3' => 'Việc Làm',
//         'card_title3' => 'Xem thêm nhiều việc làm',
//         'sub_category3' => 'Có hình ảnh không gian làm việc với máy tính và bàn làm việc',
//         'users' => 'Vũ Chế',
//         'post_date' => '20',
//         'portfolio' => [
//             // Dữ liệu về portfolio
//         ],
//         'blog' => [
//             // Dữ liệu về blog
//         ]
//     ];
    
//     return view('devfolio.profile', $data);
// });

