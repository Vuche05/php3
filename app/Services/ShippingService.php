<?php

namespace App\Services;

class ShippingService
{
    public function estimateWeight($cartItems)
    {
        // Giả định mỗi item có trọng lượng 0.5kg
        $weight = 0;

        foreach ($cartItems as $item) {
            $weight += ($item['quantity'] ?? 1) * 0.5;
        }

        return $weight; // đơn vị: kg
    }

    public function calculateShipping($address, $weight, $total)
    {
        $freeMinimum = env('FREE_SHIPPING_MINIMUM', 500000);
        $defaultCost = env('DEFAULT_SHIPPING_COST', 30000);

        if ($total >= $freeMinimum) {
            return 0;
        }

        // Tính cước dựa trên trọng lượng
        return $defaultCost + ($weight * 5000); // ví dụ thêm 5000đ mỗi kg
    }
}
