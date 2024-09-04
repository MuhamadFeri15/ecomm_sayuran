<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'profile_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
    ];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
