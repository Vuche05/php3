<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông báo hủy đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; }
        .content { padding: 20px; background: #fff; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Thông báo hủy đơn hàng #{{ $order->id }}</h2>
        </div>
        <div class="content">
            <p>Kính chào {{ $order->shipping_name }},</p>
            <p>Chúng tôi xin thông báo rằng đơn hàng #{{ $order->id }} của bạn đã được hủy theo yêu cầu.</p>
            <p><strong>Ngày hủy:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email {{ config('mail.from.address') }}.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>