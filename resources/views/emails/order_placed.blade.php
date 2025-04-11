<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; }
        .content { padding: 20px; background: #fff; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 10px; border: 1px solid #ddd; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Xác nhận đơn hàng #{{ $order->id }}</h2>
        </div>
        <div class="content">
            <p>Kính chào {{ $order->shipping_name }},</p>
            <p>Cảm ơn bạn đã đặt hàng tại {{ config('app.name') }}. Dưới đây là thông tin chi tiết đơn hàng của bạn:</p>

            <h3>Thông tin giao hàng</h3>
            <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>

            <h3>Chi tiết đơn hàng</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product ? $item->product->name : 'Sản phẩm không còn tồn tại' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * (1 - $item->discount/100), 0, ',', '.') }}₫</td>
                            <td>{{ number_format($item->price * (1 - $item->discount/100) * $item->quantity, 0, ',', '.') }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Tổng cộng</td>
                        <td class="total">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                    </tr>
                </tfoot>
            </table>

            <p>Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email {{ config('mail.from.address') }}.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>