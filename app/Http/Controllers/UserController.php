<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
    
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
    
            // Thông báo đăng nhập thành công
            session()->flash('success', 'Đăng nhập thành công!');
    
            // Chuyển hướng người dùng dựa trên vai trò
            if (Auth::user()->isAdmin()) {
                return redirect()->route('categories.index');
            }
    
            return redirect()->intended('/');
        }
    
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->except('password'))
          ->with('error', 'Đăng nhập thất bại! Vui lòng kiểm tra lại thông tin.');
    }
    

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            Auth::login($user);

            return redirect('/login')->with('success', 'Đăng ký thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đăng ký thất bại. Vui lòng thử lại!');
        }
    }


    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Tạo username từ tên người dùng
     */
    private function generateUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '', $name));
        $username = $baseUsername;
        $i = 1;
        
        // Kiểm tra xem username đã tồn tại chưa
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $i;
            $i++;
        }
        
        return $username;
    }

    // * Hiển thị danh sách người dùng (chỉ dành cho admin)
    //  */
    public function listUsers()
    {
        // Chỉ admin mới được truy cập
        if (!Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        // Lấy tất cả người dùng (bao gồm cả admin)
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function editUser(User $user)
    {
        // Chỉ admin mới được truy cập
        if (!Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        // Không cho phép chỉnh sửa user admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Không thể chỉnh sửa tài khoản admin');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateUser(Request $request, User $user)
{
    // Chỉ admin mới được truy cập
    if (!Auth::user()->isAdmin()) {
        return redirect('/')->with('error', 'Bạn không có quyền truy cập');
    }

    // Không cho phép chỉnh sửa user admin
    if ($user->role === 'admin') {
        return redirect()->route('users.index')
            ->with('error', 'Không thể chỉnh sửa tài khoản admin');
    }

    // Validate dữ liệu
    $request->validate([
        'username' => [
            'required', 
            'string', 
            'max:255', 
            Rule::unique('users')->ignore($user->id)
        ],
        'email' => [
            'required', 
            'email', 
            'max:255', 
            Rule::unique('users')->ignore($user->id)
        ],
        'name' => 'required|string|max:255',
        'role' => [
            'sometimes', // chỉ validate nếu trường role được gửi
            'in:user,admin' // cho phép cả user và admin
        ],
    ]);

    // Chuẩn bị dữ liệu cập nhật
    $updateData = [
        'username' => $request->username,
        'email' => $request->email,
        'name' => $request->name,
    ];

    // Chỉ cập nhật role nếu không phải admin và role được gửi
    if ($user->role !== 'admin' && $request->has('role')) {
        $updateData['role'] = $request->role;
    }

    // Cập nhật thông tin
    $user->update($updateData);

    return redirect()->route('users.index')
        ->with('success', 'Cập nhật người dùng thành công');
}
    /**
     * Xóa người dùng
     */
    public function destroyUser(User $user)
    {
        // Chỉ admin mới được truy cập
        if (!Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        // Không cho phép xóa user admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Không thể xóa tài khoản admin');
        }

        // Xóa người dùng
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Xóa người dùng thành công');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ], [
            'username.required' => 'Vui lòng nhập tên người dùng',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'avatar.image' => 'Ảnh đại diện phải là định dạng ảnh hợp lệ',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->fullname = $request->fullname;
        
        // Update address information
        $user->address = $request->address;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->ward = $request->ward;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $avatarName = time() . '.' . $request->avatar->getClientOriginalExtension();
            $avatarPath = 'avatars/' . $avatarName;
            $request->avatar->move(public_path('avatars'), $avatarName);

            $user->avatar = $avatarPath;
        }

        if ($user instanceof User) {
            $user->save();
        } else {
            return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
        }

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }

    public function changePassword()
    {
        $title = "Đổi mật khẩu";
        $user = Auth::user();
        return view('change-password', compact('user', 'title'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng');
        }

        $user->password = Hash::make($request->password);

        if ($user instanceof User) {
            $user->save();
        } else {
            return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
        }

        return back()->with('success', 'Đổi mật khẩu thành công');
    }

    public function forgotPassword(Request $request)
    {
        $title = 'Quên mật khẩu';
        return view('auth.forgot-password', compact('title'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Không tìm thấy tài khoản với email này'
        ]);

        $request->session()->forget(['email', 'otp_verified']);

        $user = User::where('email', $request->email)->first();

        $user->sendPasswordResetEmail();

        $request->session()->put('email', $request->email);

        return redirect()->route('password.verify-otp')
            ->with('success', 'Mã OTP đã được gửi đến email của bạn');
    }

    public function verifyOtp(Request $request)
    {
        $title = 'Xác nhận OTP';
        return view('auth.verify-otp', compact('title'));
    }

    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP',
            'otp.numeric' => 'Mã OTP phải là số',
            'otp.digits' => 'Mã OTP phải có 6 chữ số'
        ]);

        $email = $request->session()->get('email');

        if (!$email) {
            return redirect()->route('password.forgot')
                ->with('error', 'Phiên làm việc đã hết hạn. Vui lòng thực hiện lại quy trình đặt lại mật khẩu.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.forgot')
                ->with('error', 'Không tìm thấy tài khoản');
        }

        if (!$user->verifyResetToken($request->otp)) {
            return redirect()->route('password.verify-otp')
                ->with('error', 'Mã OTP không hợp lệ hoặc đã hết hạn');
        }

        $request->session()->put('otp_verified', true);

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $title = 'Đặt lại mật khẩu';
        return view('auth.reset-password', compact('title'));
    }

    public function resetPassword(Request $request)
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $email = $request->session()->get('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->clearResetToken();
            $user->save();

            $request->session()->forget(['email', 'otp_verified']);

            return redirect()->route('login')->with('success', 'Mật khẩu đã được đổi thành công. Vui lòng đăng nhập.');
        }

        return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
    }

     // Phương thức đăng nhập Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
    
            Log::info('Dữ liệu từ Google: ', (array) $googleUser);
    
            if (!$googleUser->getEmail() || !$googleUser->getId()) {
                throw new \Exception('Dữ liệu Google không đầy đủ');
            }
    
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'username' => $googleUser->getName(),
                    'password' => bcrypt('google'),
                    'oauth_provider' => 'google',
                    'oauth_id' => $googleUser->getId(),
                ]
            );
    
            Log::info('User sau khi lưu vào database: ', (array) $user);
    
            Auth::login($user, true); 
            session(['user_id' => $user->id]);
    
            return redirect()->route('home')->with('success', 'Đăng nhập Google thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi đăng nhập Google: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }

    //address
    public function address()
    {
        $title = "Thêm địa chỉ";
        $user = Auth::user();
        $addresses = $user->addresses ?? collect();
        Log::info('Addresses:', ['addresses' => $addresses]);
        return view('profile.addresses', compact('addresses', 'title', 'user'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'street' => 'required|string',
            'is_default' => 'nullable|boolean',
        ], [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố',
            'district.required' => 'Vui lòng chọn quận/huyện',
            'ward.required' => 'Vui lòng chọn xã/phường',
            'street.required' => 'Vui lòng nhập địa chỉ cụ thể',
        ]);

        $user = Auth::user();

        $address = new Address([
            'user_id' => $user->id,
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'street' => $request->street,
            'is_default' => $request->is_default ? true : false,
        ]);

        if ($request->is_default) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address->save();

        return redirect()->route('profile.addresses')->with('success', 'Thêm địa chỉ thành công');
    }

    public function setAddress($id)
    {
        $address = Address::findOrFail($id);
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            return redirect()->route('profile.addresses')
                ->with('error', 'Địa chỉ không thuộc về bạn.');
        }
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return redirect()->route('profile.addresses')
            ->with('success', 'Đặt địa chỉ mặc định thành công.');
    }
    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return redirect()->route('profile.address')->with('success', 'Xóa địa chỉ thành cong');
    }
}