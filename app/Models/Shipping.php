<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'profile_id',
        'shipping_method',
        'tracking_number',
        'shipping_date',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function profile() {
        return $this->belongsTo(Profile::class);
    }
}
