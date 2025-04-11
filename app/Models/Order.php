<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'shipping_phone',
        'shipping_name',
        'note',
        'coupon_id',
        'discount_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    
    // Tính tổng giá trị đơn hàng trước khi giảm giá
    public function getSubtotalAttribute()
    {
        return $this->total_amount + $this->discount_amount;
    }
}