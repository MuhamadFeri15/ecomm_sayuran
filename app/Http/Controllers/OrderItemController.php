<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::all();
        return Inertia::render('OrderItem/Index', ['orderItems' => $orderItems]);
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
        $orderItem = new OrderItem();
        $orderItem->fill($request->all());
        $orderItem->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        if (!$orderItem) {
            return redirect()->route('order-items.index')->with('error', 'Order item not found');
        } else {
            return Inertia::render('OrderItem/Show', ['orderItem' => $orderItem]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem, $id)
    {
        $orderItem = OrderItem::find($id);
        if (!$orderItem) {
            return redirect()->route('order-items.index')->with('error', 'Order item not found');
        } else {
            return Inertia::render('OrderItem/Edit', ['orderItem' => $orderItem]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $orderItem = OrderItem::find($id);

         if (!$orderItem) {
            return redirect()->route('order-items.index')->with('error', 'Order item not found');
        }


        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);


        $orderItem->fill($validatedData);
        $orderItem->save();


        return redirect()->route('order-items.index')->with('success', 'Order item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem, $id)
    {
        $orderItem = OrderItem::find($id);
        if (!$orderItem) {
            return redirect()->route('order-items.index')->with('error', 'Order item not found');
        }

        $orderItem->delete();

        return redirect()->route('order-items.index')->with('success', 'Order item deleted successfully');
    }

    public function updateQuantity($order_id, $product_id, Request $request)
    {
        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min=1',
        ]);

        // Cari item order berdasarkan order_id dan product_id
        $orderItem = OrderItem::where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->first();

        if (!$orderItem) {
            return redirect()->back()->withErrors('Item not found.');
        }

        // Perbarui kuantitas item
        $orderItem->quantity = $request->input('quantity');
        $orderItem->save();

        // Redirect dengan flash message untuk Inertia
        return redirect()->back()->with('success', 'Quantity updated successfully.');
    }

    // Method untuk menghapus item dari order
    public function deleteItem($order_id, $product_id)
    {
        $orderItem = OrderItem::where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->first();

        if (!$orderItem) {
            return redirect()->back()->withErrors('Item not found.');
        }

        $orderItem->delete();

        // Redirect dengan flash message untuk Inertia
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}


