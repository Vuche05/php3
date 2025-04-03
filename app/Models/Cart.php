<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Tính tổng giá trong giỏ hàng
    public function getTotalPriceAttribute()
    {
        $productPrice = $this->product->price;
        $discount = $this->product->discount ?? 0;
        $discountedPrice = $productPrice * (1 - ($discount / 100));
        return $discountedPrice * $this->quantity;
    }
}