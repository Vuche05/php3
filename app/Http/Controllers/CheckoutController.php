<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }
        
        $total = $cartItems->sum(function($item) {
            $price = $item->product->discount 
                ? $item->product->price * (1 - $item->product->discount/100) 
                : $item->product->price;
            return $price * $item->quantity;
        });
        
        return view('checkout.index', compact('cartItems', 'total'));
    }
    
    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,vnpay',
            'note' => 'nullable|string|max:500',
        ]);
        \Log::info('Checkout request:', $request->all());
        
        // Get cart items
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }
        
        // Calculate total
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product->discount 
                ? $item->product->price * (1 - $item->product->discount/100) 
                : $item->product->price;
            return $price * $item->quantity;
        });
        
        // Apply coupon discount if available
        $couponDiscount = 0;
        $couponId = null;
        
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon')['discount'];
            $couponId = Session::get('coupon')['id'];
        }
        
        $total = $subtotal - $couponDiscount;
        
        // Begin transaction
        try {
            DB::beginTransaction();
            
            // Check inventory
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->quantity) {
                    throw new \Exception("Sản phẩm '{$item->product->name}' chỉ còn {$item->product->quantity} sản phẩm trong kho.");
                }
            }
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_name' => $validatedData['shipping_name'],
                'shipping_address' => $validatedData['shipping_address'],
                'shipping_phone' => $validatedData['shipping_phone'],
                'note' => $validatedData['note'] ?? '',
                'coupon_id' => $couponId,
                'discount_amount' => $couponDiscount,
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $price = $item->product->price;
                $discount = $item->product->discount ?? 0;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'discount' => $discount
                ]);
                
                // Update product quantity
                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
            }
            
            // Update coupon usage if used
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->used_count += 1;
                    $coupon->save();
                }
            }
            
            // Process payment based on method
            if ($validatedData['payment_method'] === 'cod') {
                // Create payment record for COD
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'cod',
                    'amount' => $total,
                    'status' => 'pending',
                ]);
                
                // Clear cart and coupon
                Cart::where('user_id', Auth::id())->delete();
                Session::forget('coupon');
                
                DB::commit();
                
                return redirect()->route('checkout.success', ['order' => $order->id]);
                
            } elseif ($validatedData['payment_method'] === 'vnpay') {
                // Create payment record for VNPay
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'vnpay',
                    'amount' => $total,
                    'status' => 'pending',
                ]);
                
                DB::commit();
                
                // Redirect to VNPay
                return $this->redirectToVNPay($order, $total);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    private function redirectToVNPay(Order $order, $amount)
    {
        // VNPay configuration
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_ReturnUrl = route('checkout.vnpay.return');
        $vnp_TmnCode = env('VNPAY_TMN_CODE', 'JSEXCJ9L'); // Merchant code
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'H3X3IZQ45N58QUDVJCTKAAY8D9Y1QY9X'); // Secret key
        
        $vnp_TxnRef = $order->id . '-' . time(); // Reference code
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay requires amount in VND cents
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $this->getClientIp();
        $vnp_BankCode = ''; // Leave empty to show bank selection
        
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        // Update payment with transaction reference
        $payment = Payment::where('order_id', $order->id)->first();
        $payment->transaction_id = $vnp_TxnRef;
        $payment->save();
        
        // Redirect to VNPay payment gateway
        return redirect($vnp_Url);
    }
    
    public function vnpayReturn(Request $request)
    {
        // VNPay configuration
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'your-hash-secret');
        
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // Extract order ID from transaction reference
        $orderParts = explode('-', $request->input('vnp_TxnRef'));
        $orderId = $orderParts[0];
        
        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($orderId);
            $payment = Payment::where('order_id', $order->id)->first();
            
            if ($secureHash == $vnp_SecureHash) { // Valid signature
                if ($request->input('vnp_ResponseCode') == '00') {
                    // Payment successful
                    $payment->status = 'completed';
                    $payment->transaction_data = $request->all();
                    $payment->save();
                    
                    $order->status = 'processing';
                    $order->save();
                    
                    // Clear cart and coupon
                    Cart::where('user_id', Auth::id())->delete();
                    Session::forget('coupon');
                    
                    DB::commit();
                    
                    return redirect()->route('checkout.success', ['order' => $order->id]);
                } else {
                    // Payment failed
                    $payment->status = 'failed';
                    $payment->transaction_data = $request->all();
                    $payment->save();
                    
                    $order->status = 'failed';
                    $order->save();
                    
                    DB::commit();
                    
                    return redirect()->route('checkout.failure', [
                        'order' => $order->id,
                        'message' => 'Thanh toán thất bại: ' . $request->input('vnp_ResponseCode')
                    ]);
                }
            } else {
                // Invalid signature
                $payment->status = 'failed';
                $payment->transaction_data = $request->all();
                $payment->save();
                
                $order->status = 'failed';
                $order->save();
                
                DB::commit();
                
                return redirect()->route('checkout.failure', [
                    'order' => $order->id,
                    'message' => 'Chữ ký không hợp lệ'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.failure', [
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
    
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        return view('checkout.success', compact('order'));
    }
    
    public function failure(Request $request)
    {
        $orderId = $request->input('order');
        $message = $request->input('message', 'Thanh toán thất bại');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->user_id === Auth::id()) {
                return view('checkout.failure', compact('order', 'message'));
            }
        }
        
        return view('checkout.failure', compact('message'));
    }
    
    private function getClientIp()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '127.0.0.1';
        return $ipaddress;
    }

    public function list()
{
    $orders = Order::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    // You can define shipping options here if needed based on your view
    $shippingOptions = [
        [
            'code' => 'standard',
            'name' => 'Giao hàng tiêu chuẩn',
            'description' => 'Thời gian giao hàng: 3-5 ngày',
            'cost' => 30000,
            'delivery_time' => '3-5 ngày'
        ],
        [
            'code' => 'express',
            'name' => 'Giao hàng nhanh',
            'description' => 'Thời gian giao hàng: 1-2 ngày',
            'cost' => 50000,
            'delivery_time' => '1-2 ngày'
        ],
        [
            'code' => 'same_day',
            'name' => 'Giao hàng trong ngày',
            'description' => 'Chỉ áp dụng trong nội thành',
            'cost' => 70000,
            'delivery_time' => 'Trong ngày'
        ]
    ];

    // Check if any order qualifies for free shipping
    // This is just an example - adjust this logic based on your business requirements
    $freeShipping = false;
    
    return view('checkout.list', compact('orders', 'shippingOptions', 'freeShipping'));
}


    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('checkout.list')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        // Eagerly load the items relationship and the associated product
        $order->load(['items.product', 'payment']);
        
        // For the order details view
        $freeShipping = $order->shipping_cost == 0;
        
        return view('checkout.show', compact('order', 'freeShipping'));
    }

    public function cancel(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('checkout.list')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        // Check if the order can be cancelled (only pending orders)
        if ($order->status !== 'pending') {
            return redirect()->route('checkout.show', $order)->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại');
        }
        
        try {
            DB::beginTransaction();
            
            // Update order status
            $order->status = 'cancelled';
            $order->save();
            
            // Update payment status if exists
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->status = 'cancelled';
                $payment->save();
            }
            
            // Return products to inventory
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('checkout.show', $order)->with('success', 'Đơn hàng đã được hủy thành công');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.show', $order)->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updateShipping(Request $request)
    {
        $validatedData = $request->validate([
            'shipping_method' => 'required|string',
        ]);
        
        // Get shipping cost based on selected method
        $shippingOptions = [
            'standard' => 30000,
            'express' => 50000,
            'same_day' => 70000,
        ];
        
        $shippingCost = $shippingOptions[$validatedData['shipping_method']] ?? 30000;
        
        // Here you would update the user's preferred shipping method
        // For example, you might store this in the user's session or profile
        
        return redirect()->route('checkout.list')->with('success', 'Phương thức vận chuyển đã được cập nhật.');
    }

    public function rebuy(Order $order)
    {
        // Kiểm tra nếu đơn hàng thuộc về người dùng đang đăng nhập
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('checkout.list')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        // Lấy thông tin các sản phẩm trong đơn hàng
        $orderItems = $order->items;
        $addedItems = 0;
        $unavailableItems = [];
        
        foreach ($orderItems as $item) {
            // Kiểm tra xem sản phẩm còn tồn tại và còn hàng không
            $product = Product::find($item->product_id);
            
            if ($product && $product->quantity > 0) {
                // Kiểm tra số lượng có sẵn
                $quantityToAdd = min($item->quantity, $product->quantity);
                
                // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                $cartItem = Cart::where('user_id', Auth::id())
                                ->where('product_id', $product->id)
                                ->first();
                
                if ($cartItem) {
                    // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
                    $cartItem->quantity += $quantityToAdd;
                    $cartItem->save();
                } else {
                    // Thêm sản phẩm mới vào giỏ hàng
                    Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'quantity' => $quantityToAdd
                    ]);
                }
                
                $addedItems++;
                
                // Nếu số lượng có sẵn ít hơn số lượng trong đơn hàng gốc, thêm vào danh sách không có sẵn
                if ($quantityToAdd < $item->quantity) {
                    $unavailableItems[] = [
                        'name' => $product->name,
                        'requested' => $item->quantity,
                        'available' => $quantityToAdd
                    ];
                }
            } else {
                // Sản phẩm không tồn tại hoặc hết hàng
                $unavailableItems[] = [
                    'name' => $item->product ? $item->product->name : "Sản phẩm ID: {$item->product_id}",
                    'requested' => $item->quantity,
                    'available' => 0
                ];
            }
        }
        
        // Thông báo kết quả
        if ($addedItems > 0) {
            $message = "{$addedItems} sản phẩm đã được thêm vào giỏ hàng.";
            
            if (count($unavailableItems) > 0) {
                $message .= " Một số sản phẩm không còn đủ số lượng hoặc không còn tồn tại.";
            }
            
            return redirect()->route('cart.index')->with('success', $message);
        } else {
            return redirect()->route('checkout.show', $order)->with('error', 'Không thể mua lại đơn hàng này. Các sản phẩm không còn tồn tại hoặc hết hàng.');
        }
    }

}