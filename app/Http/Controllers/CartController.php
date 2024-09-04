<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Auth::profile()->cart()->where('is_checked_out', false)->with('product')->get();

        return Inertia::render('Cart/Index', [
            'carts' => $carts
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' =>'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        $cart = Cart::firstOrCreate([
            'profile_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $cart->quantity += $request->quantity;
        $cart->total_price = $cart->price * $cart->quantity;
        $cart->save();

        return redirect()->back()->with('success', 'Product added to cart successfully!');

}

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->total_price = $cart->price * $request->quantity;
        $cart->save();

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
        {
            $cart = Cart::findOrFail($id);
            $cart->delete();

            return redirect()->back()->with('success', 'Product removed from cart successfully!');
        }


    public function checkout($id)
{
    $profile = Auth::user()->profile;

    $carts = Cart::where('profile_id', $profile->id)
        ->where('is_checked_out', false)
        ->get();

    if ($carts->isEmpty()) {
        return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
    }

    DB::transaction(function () use ($carts) {
        foreach ($carts as $cart) {
            $cart->update(['is_checked_out' => true]);
        }
    });

    return redirect()->route('carts.index')->with('success', 'Your cart has been checked out.');
}
}


    /**
     * Remove the specified resource from storage.
     */
