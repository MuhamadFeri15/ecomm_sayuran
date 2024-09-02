<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'order_id',
      'payment_method',
      'payment_status',
      'payment_date',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    

}
