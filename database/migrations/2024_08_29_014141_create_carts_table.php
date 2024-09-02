<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_id',
        'product_id',
        'order_id',
        'orderItem_id',
        'is_checked_out',
        'quantity',
    ];

    // Relasi ke model Profile
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // Relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke model OrderItem
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'orderItem_id');
    }
}
