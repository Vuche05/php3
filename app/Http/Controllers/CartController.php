<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(function($item) {
            $price = $item->product->discount 
                ? $item->product->price * (1 - $item->product->discount/100) 
                : $item->product->price;
            return $price * $item->quantity;
        });
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);

            // Check product availability
            if ($product->quantity < $request->quantity) {
                return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ!');
            }

            // Use transaction for atomic operation
            DB::transaction(function () use ($product, $request) {
                $cartItem = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($cartItem) {
                    // Check total quantity
                    $newQuantity = $cartItem->quantity + $request->quantity;
                    if ($newQuantity > $product->quantity) {
                        throw new \Exception('Vượt quá số lượng sản phẩm trong kho');
                    }
                    $cartItem->quantity = $newQuantity;
                    $cartItem->save();
                } else {
                    Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'quantity' => $request->quantity
                    ]);
                }
            });

            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Cart $cart)
    {
        try {
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            // Verify ownership and product availability
            if ($cart->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Bạn không có quyền thực hiện thao tác này!');
            }

            $product = $cart->product;
            if ($request->quantity > $product->quantity) {
                return redirect()->back()->with('error', 'Số lượng vượt quá sản phẩm trong kho!');
            }

            $cart->update(['quantity' => $request->quantity]);

            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function remove(Cart $cart)
    {
        try {
            // Verify ownership
            if ($cart->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Bạn không có quyền thực hiện thao tác này!');
            }

            $cart->delete();

            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function clear()
    {
        try {
            Cart::where('user_id', Auth::id())->delete();

            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được làm trống!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}