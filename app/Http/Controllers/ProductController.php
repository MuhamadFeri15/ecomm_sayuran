<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil filter dari query string
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // Query dasar untuk produk
        $query = Product::query();

        // Menerapkan filter jika ada
        if ($category) {
            $query->where('category_id', $category);
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Mengambil hasil query
        $products = $query->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only(['category', 'min_price', 'max_price']),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')->store('products', 'public');


            $validatedData['image'] = $imagePath;
        }

        Product::create($validatedData);

        return redirect()->route('product')->with('success', 'Produk berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return inertia::render('products.show', compact('product'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return inertia::render('products.edit', compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|text',
            'price' => 'required|integer|min:0',
            'weight_product' => 'required|integer|min:0',
           'stock' => 'required|integer|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Tambahkan validasi jika ingin mengupload gambar
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        // Perbarui produk dengan data yang sudah divalidasi
        $product->update($validatedData);

        // Redirect ke halaman produk dengan pesan sukses
        return inertia::render('product.index')->with('success', 'Product updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            // Mengarahkan ke halaman indeks produk dengan pesan sukses
            return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
        }

        // Jika produk tidak ditemukan, arahkan kembali dengan pesan error
        return redirect()->route('product.index')->with('error', 'Product not found!');
    }

    public function trash() {
        $products = Product::onlyTrashed()->get();
        return Inertia::render('Products/Trash', [
            'products' => $products,
        ]);
    }

    public function restore($id) {
        $product =  Product::onlyTrashed()->where('id', $id)->first();

        if ($product) {
            $product->restore();
            return redirect()->route('product')->with('success', 'data sudah dikembalikan.');
        }
        return redirect()->route('product')->with('error', 'data gagal dikembalikan');


    }

    public function permanentDelete($id)
{
    // Cari produk yang sudah dihapus dengan soft delete
    $product = Product::onlyTrashed()->where('id', $id)->first();

    if ($product) {
        // Hapus permanen produk tersebut
        $product->forceDelete();
        return redirect()->route('product.index')->with('success', 'Data yang tersimpan dihapus permanen.');
    } else {
        return redirect()->route('product.index')->with('error', 'Data gagal dihapus permanen.');
    }
}

    public function search() {
        $query = request('query');

        if (empty($query)) {
            return redirect()->route('products.index')->with('error', 'Please enter a search term.');
        }

        $products = Product::where('name', 'LIKE', '%' . $query . '%')->paginate(10);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => [
                'query' => $query,
            ],
        ]);

    }




}
