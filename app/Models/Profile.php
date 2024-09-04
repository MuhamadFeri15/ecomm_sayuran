<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{

    use SoftDeletes;

    protected $fillable = [

      'address',
      'phone_number',
      'image'
    ];

    public function login(){
        return $this->belongsTo(Login::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }

    public function favourite() {
        return $this->hasMany(Favourite::class);
    }

    public function shipping(){
        return $this->hasOne(Shipping::class);
    }

    public function cart() {
        return $this->hasMany(Cart::class);
    }

    public function payment() {
        return $this->hasMany(Payment::class);
    }
}

