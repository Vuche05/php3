<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
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

            // Chuyển hướng người dùng dựa trên vai trò
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->except('password'));
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

        $user = User::create([
            'username' => $request->username,
            'name' => $request->username, // Sử dụng username là name nếu không có trường name
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Mặc định là user thường
        ]);

        Auth::login($user);

        return redirect('/');
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Chuyển hướng đến Google để xác thực
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Tìm user theo google_id hoặc email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();
                        
            // Nếu không tìm thấy, tạo user mới
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $this->generateUsername($googleUser->name),
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(rand(1, 10000)), // Mật khẩu ngẫu nhiên
                    'role' => 'user',
                ]);
            } elseif (!$user->google_id) {
                // Nếu user đã tồn tại nhưng chưa liên kết với Google
                $user->update(['google_id' => $googleUser->id]);
            }
            
            Auth::login($user);
            return redirect('/');
            
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.']);
        }
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
}