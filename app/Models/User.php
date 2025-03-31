<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['username', 'name', 'email', 'password', 'fullname', 'avatar', 'role', 'token', 'reset_token', 'reset_token_expires_at'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Kiểm tra xem người dùng có phải là admin không
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Lấy danh sách đơn hàng của người dùng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Lấy danh sách địa chỉ của người dùng
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function generateResetToken()
    {
        $this->reset_token = sprintf("%06d", mt_rand(100000, 999999));
        $this->reset_token_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->reset_token;
    }

    public function verifyResetToken($token)
    {
        // Kiểm tra xem token có tồn tại không
        if (!$this->reset_token) {
            return false;
        }

        // Kiểm tra token có khớp không
        if ($this->reset_token !== $token) {
            return false;
        }

        // Kiểm tra token có còn hiệu lực không
        $isValid = $this->reset_token_expires_at && 
                $this->reset_token_expires_at > now();

        // Nếu token hợp lệ, có thể muốn xóa token để tránh sử dụng lại
        if ($isValid) {
            // Không xóa token ngay lập tức để người dùng có thể thử lại
            // Sẽ xóa sau khi đặt lại mật khẩu thành công
            return true;
        }

        return false;
    }

    public function clearResetToken()
    {
        $this->reset_token = null;
        $this->reset_token_expires_at = null;
        $this->save();
    }

    public function sendPasswordResetEmail()
    {
        // Xóa token cũ trước khi tạo mới
        $this->clearResetToken();

        // Tạo OTP mới
        $otp = $this->generateResetToken();

        // Gửi email
        Mail::send('emails.password-reset', ['otp' => $otp], function($message) {
            $message->to($this->email)
                    ->subject('Mã OTP Đặt Lại Mật Khẩu');
        });
    }
}