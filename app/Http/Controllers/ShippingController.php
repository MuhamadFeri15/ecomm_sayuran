<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipping = Shipping::all();
        return Inertia::render('Shipping/Index', [
            'shipping' => $shipping
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Shipping/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'profile_id' => 'required|integer',
            'shipping_method' => 'required|string',
            'tracking_number' => 'nullable|string',
            'shipping_date' => 'required|date',
        ]);

        Shipping::create($request->all());
        return redirect()->route('shippings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shipping = Shipping::findOrFail($id);
        return Inertia::render('Shippings/Show', [
            'shipping' => $shipping,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shipping = Shipping::findOrFail($id);
        return Inertia::render('Shippings/Edit', [
            'shipping' => $shipping,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'profile_id' => 'required|integer',
            'shipping_method' => 'required|string',
            'tracking_number' => 'nullable|string',
            'shipping_date' => 'required|date',
        ]);

        $shipping = Shipping::findOrFail($id);
        $shipping->update($request->all());
        return redirect()->route('shippings.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();
        return redirect()->route('shippings.index');
    }
}
