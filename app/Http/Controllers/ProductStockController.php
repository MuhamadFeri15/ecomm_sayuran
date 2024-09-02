<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productStocks = ProductStock::with('product')->get(); // Mengambil semua stok produk dan memuat data produk terkait
        return Inertia::render('ProductStock/Index', [
            'productStocks' => $productStocks
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStock $productStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductStock $productStock, $id)
    {
        $productStock = ProductStock::with('product')->findOrFail($id); // Mengambil stok produk berdasarkan ID dan memuat data produk terkait
        return Inertia::render('ProductStock/Edit', [
            'productStock' => $productStock
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    { $productStock = ProductStock::findOrFail($id); // Mengambil stok produk berdasarkan ID atau gagal jika tidak ditemukan

        // Validasi data yang diinput oleh pengguna
        $request->validate([
            'total_available' => 'required|integer|min:0',
            'total_defect' => 'required|integer|min:0',
        ]);

        // Memperbarui stok produk dengan data yang baru
        $productStock->update([
            'total_available' => $request->input('total_available'),
            'total_defect' => $request->input('total_defect'),
        ]);

        // Mengarahkan kembali ke halaman indeks dengan pesan sukses
        return Inertia::render('ProductStocks/Index', [
            'success' => 'Product stock updated successfully.',
            'productStocks' => ProductStock::all() // Menyediakan data terbaru
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductStock $productStock)
    {
        //
    }
}
