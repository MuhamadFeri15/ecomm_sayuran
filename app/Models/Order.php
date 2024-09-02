<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{

    protected $fillable = [
        'profile_id',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
    ];

    // Optionally, define relationships
    public function login()
    {
        return $this->belongsTo(Login::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'shipping_address');
    }

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping() {
        return $this->hasOne(Shipping::class);
    }

    public function cart() {
        return $this->hasMany(Cart::class);
    }
}
