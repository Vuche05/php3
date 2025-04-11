<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupon.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['value' => 'Giá trị phần trăm không thể vượt quá 100%.']);
        }

        if (!isset($validatedData['is_active'])) {
            $validatedData['is_active'] = false;
        }

        Coupon::create($validatedData);

        return redirect()->route('coupon.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupon.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['value' => 'Giá trị phần trăm không thể vượt quá 100%.']);
        }

        if (!isset($validatedData['is_active'])) {
            $validatedData['is_active'] = false;
        }

        $coupon->update($validatedData);

        return redirect()->route('coupon.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return redirect()->route('coupon.index')
                ->with('success', 'Mã giảm giá đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('coupon.index')
                ->with('error', 'Không thể xóa mã giảm giá này vì nó đang được sử dụng!');
        }
    }

    public function generateCode()
    {
        $code = strtoupper(Str::random(8));
        
        // Ensure code is unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }
        
        return response()->json(['code' => $code]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $code = $request->input('coupon_code');
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại!');
        }

        // Calculate cart total
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product->discount 
                ? $item->product->price * (1 - $item->product->discount/100) 
                : $item->product->price;
            return $price * $item->quantity;
        });

        if (!$coupon->isValid($subtotal)) {
            // Determine the specific reason why the coupon is invalid
            if (!$coupon->is_active) {
                return redirect()->back()->with('error', 'Mã giảm giá này không còn hoạt động!');
            }
            
            if ($coupon->starts_at && $coupon->starts_at > now()) {
                return redirect()->back()->with('error', 'Mã giảm giá này chưa có hiệu lực!');
            }
            
            if ($coupon->expires_at && $coupon->expires_at < now()) {
                return redirect()->back()->with('error', 'Mã giảm giá này đã hết hạn!');
            }
            
            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                return redirect()->back()->with('error', 'Mã giảm giá này đã đạt giới hạn sử dụng!');
            }
            
            if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                return redirect()->back()->with('error', 'Đơn hàng của bạn chưa đạt giá trị tối thiểu để sử dụng mã này! (Tối thiểu: ' . number_format($coupon->min_order_amount, 0, ',', '.') . 'đ)');
            }
            
            return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ!');
        }

        // Store coupon in session
        Session::put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->calculateDiscount($subtotal)
        ]);

        return redirect()->back()->with('success', 'Mã giảm giá đã được áp dụng thành công!');
    }

    public function remove()
    {
        Session::forget('coupon');
        return redirect()->back()->with('success', 'Đã xóa mã giảm giá!');
    }
    
}