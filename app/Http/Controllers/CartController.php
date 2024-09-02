<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        // Mendapatkan profil pengguna yang sedang login
        $profile = Auth::user()->profile;

        // Mengambil semua item keranjang untuk pengguna yang sedang login
        $carts = Cart::where('profile_id', $profile->id)->with('product')->get();

        return Inertia::render('Carts/Index', [
            'carts' => $carts,
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
        $profile = Auth::user()->profile;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::create([
            'profile_id' => $profile->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'is_checked_out' => false,
        ]);

        return redirect()->route('carts.index')->with('success', 'Product added to cart.');
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
            $cart->update([
                'quantity' => $request->quantity,
            ]);

            return redirect()->route('carts.index')->with('success', 'Cart updated successfully.');
        }
        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Item removed from cart.');
    }


    public function checkout()
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
