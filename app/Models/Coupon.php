<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'description',
        'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getInvalidReason()
    {
        $reasons = [];
        
        if (!$this->is_active) {
            $reasons[] = 'Không kích hoạt';
        }
        
        if ($this->starts_at && $this->starts_at > now()) {
            $reasons[] = 'Chưa đến thời gian bắt đầu: ' . $this->starts_at;
        }
        
        if ($this->expires_at && $this->expires_at < now()) {
            $reasons[] = 'Đã hết hạn: ' . $this->expires_at;
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            $reasons[] = 'Đã đạt giới hạn sử dụng';
        }
        
        return implode(', ', $reasons);
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scope for active coupons
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('used_count < usage_limit');
                    });
    }

    // Calculate discount amount
    public function calculateDiscount($subtotal)
    {
        // Check minimum order amount
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;
        
        if ($this->type === 'fixed') {
            $discount = $this->value;
        } elseif ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
        }

        // Apply maximum discount if set
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        // Ensure discount is not more than the subtotal
        return min($discount, $subtotal);
    }

    // Get formatted value for display
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'fixed') {
            return number_format($this->value, 0, ',', '.') . 'đ';
        } else {
            return $this->value . '%';
        }
    }

    // Check if coupon is valid
    public function isValid($subtotal = 0)
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check dates
        if ($this->starts_at && $this->starts_at > now()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < now()) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // Check minimum order amount
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return false;
        }

        return true;
    }
}