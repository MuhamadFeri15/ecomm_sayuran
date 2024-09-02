<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('profile_id', Auth::id())->paginate(10);
        return Inertia::render('Orders/Index', ['orders' => $orders]);
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
        // Validasi data yang diinput oleh pengguna
        $request->validate([
            'profile_id' => 'required|integer|exists:profiles,id',
            'total_amount' => 'required|integer|min:0',
            'payment_method' => 'required|integer',
            'status' => 'required|in:packing,delivered,order_received',
        ]);

        // Membuat pesanan baru
        Order::create([
            'profile_id' => $request->input('profile_id'),
            'total_amount' => $request->input('total_amount'),
            'payment_method' => $request->input('payment_method'),
            'status' => $request->input('status'),
        ]);

        // Mengarahkan kembali ke halaman daftar pesanan dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('profile')->findOrFail($id); // Mengambil pesanan berdasarkan ID dan memuat data profil terkait
        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id); // Mengambil pesanan berdasarkan ID
        return Inertia::render('Orders/Edit', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi data yang diinput oleh pengguna
        $request->validate([
            'total_amount' => 'required|integer|min:0',
            'payment_method' => 'required|integer',
            'status' => 'required|in:packing,delivered,order_received',
        ]);

        // Memperbarui pesanan dengan data yang baru
        $order->update([
            'total_amount' => $request->input('total_amount'),
            'payment_method' => $request->input('payment_method'),
            'status' => $request->input('status'),
        ]);

        // Mengarahkan kembali ke halaman detail pesanan dengan pesan sukses
        return redirect()->route('orders.show', $order->id)->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->forceDelete(); // Menghapus pesanan (soft delete)

        // Mengarahkan kembali ke halaman daftar pesanan dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
