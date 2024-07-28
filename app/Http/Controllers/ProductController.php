<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function safety()
    {
        if (Auth::user()->role != 'admin') {
            abort('401');
        }
    }

    public function index()
    {
        $this->safety();

    $products = Product::with('supplier')
                        ->select('products.*', DB::raw('SUM(suppliers.quantity) as total_quantity'))
                        ->join('suppliers', 'suppliers.product_name', '=', 'products.name')
                        ->groupBy(
                            'products.id', 
                            'products.kode_barang', 
                            'products.name', 
                            'products.category_id', 
                            'products.brand', 
                            'products.harga_awal', 
                            'products.price', 
                            'products.stock', 
                            'products.expired', 
                            'products.status', 
                            'products.photo', 
                            'products.created_at', 
                            'products.updated_at',
                            'products.description',
                            'products.supplier_id'
                        )
                        ->get();

    return view('dashboard.produk.index', compact('products'));
    }

    public function create()
{
    $this->safety();
    $categories = Category::all();
    $suppliers = Supplier::all();

    $existingProductNames = Product::pluck('name')->toArray();
    $availableSuppliers = $suppliers->filter(function($supplier) use ($existingProductNames) {
        return !in_array($supplier->product_name, $existingProductNames);
    });

    return view('dashboard.produk.create', compact('categories', 'suppliers', 'availableSuppliers'));
}

    public function store(Request $request)
{
    $this->safety();

    $request->validate([
        'name' => 'required',
        'category_id' => 'required',
        'brand' => 'required',
        'price' => 'required',
        'description' => 'required',
        'stock' => 'required',
        'photo' => 'required',
        'kode_barang' => 'required',
        'harga_awal' => 'nullable|numeric'
    ]);

    $photo = $request->file('photo');
    $photo->storeAs('public/photos', $photo->hashName());

    Product::create([
        'photo' => $photo->hashName(),
        'name'  => $request->name,
        'category_id'  => $request->category_id,
        'brand'  => $request->brand,
        'price'  => $request->price,
        'expired'  => $request->expired,
        'description'  => $request->description,
        'stock'  => $request->stock,
        'status' => $request->status,
        'kode_barang' => $request->kode_barang,
        'harga_awal' => $request->harga_awal
    ]);

    return redirect()->route('product.index')->with('success', 'Data berhasil disimpan');
}

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $products = Product::all()->except($id);

        return view('dashboard.produk.show', compact('product', 'products'));
    }

    public function edit(Product $product)
    {
        $this->safety();

        $categories = Category::all();

        return view('dashboard.produk.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $this->safety();

    $request->validate([
        // 'id' => 'required|unique:products,id,' . $product->id,
        'name' => 'required',
        'category_id' => 'required',
        'brand' => 'required',
        'price' => 'required',
        'description' => 'required',
        'stock' => 'required',
        'kode_barang' => 'required',
        'harga_awal' => 'nullable|numeric'
    ]);

    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photo->storeAs('public/photos', $photo->hashName());

        $product->update([
            'photo' => $photo->hashName(),
            'name'  => $request->name,
            'category_id'  => $request->category_id,
            'brand'  => $request->brand,
            'price'  => $request->price,
            'expired'  => $request->expired,
            'description'  => $request->description,
            'stock'  => $request->stock,
            'status'  => $request->status,
            'kode_barang' => $request->kode_barang,
            'harga_awal' => $request->harga_awal
        ]);
    } else {
        $product->update([
            'name'  => $request->name,
            'category_id'  => $request->category_id,
            'brand'  => $request->brand,
            'price'  => $request->price,
            'expired'  => $request->expired,
            'description'  => $request->description,
            'stock'  => $request->stock,
            'status'  => $request->status,
            'kode_barang' => $request->kode_barang,
            'harga_awal' => $request->harga_awal
        ]);
    }

    return redirect()->route('product.index')->with('success', 'Data berhasil disimpan');
}
    public function destroy(Product $product)
    {
        $this->safety();

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Data berhasil dihapus');
    }

    public function getSupplierProduct($productName)
{
    $supplier = Supplier::where('product_name', $productName)->first();
    
    if ($supplier) {
        return response()->json([
            'product_name' => $supplier->product_name ?? 'N/A',
            'product_brand' => $supplier->product_brand ?? 'N/A',
            'price' => $supplier->price ?? 'N/A',
            'expired' => $supplier->expired ?? 'N/A',
            'stock' => $supplier->quantity ?? 'N/A',
        ]);
    }

    return response()->json(['error' => 'Product not found'], 404);
}


}
