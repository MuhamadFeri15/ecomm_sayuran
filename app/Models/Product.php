<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_name',
        'weight_product',
        'price',
        'stock',
        'image'
    ];


    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }

    public function cart() {
        return $this->hasMany(Cart::class);
    }

    public function favourite() {
        return $this->hasMany(Favourite::class);
    }




    protected $casts = [
        'price' => 'integer',
        'weight_product' => 'integer',
        'stock' => 'integer',
    ];
}
