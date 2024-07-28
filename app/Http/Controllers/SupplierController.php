<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('dashboard.suppliers.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'kode_supplier' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'product_name' => 'required|string|max:255',
        'product_brand' => 'required|string|max:255',
        'quantity' => 'required|integer',
        'price' => 'required|integer',
        'harga_beli' => 'required|integer',
        'expired' => 'required|date',
        'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $supplier = new Supplier();
    $supplier->kode_supplier = $request->kode_supplier;
    $supplier->name = $request->name;
    $supplier->phone = $request->phone;
    $supplier->product_name = $request->product_name;
    $supplier->product_brand = $request->product_brand;
    $supplier->quantity = $request->quantity;
    $supplier->price = $request->price;
    $supplier->harga_beli = $request->harga_beli;
    $supplier->expired = $request->expired;

    if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $filename = $file->hashName();
        $file->storeAs('public/photos', $filename);
        $supplier->payment_proof = $filename;
    }

    $supplier->save();

    // Check if the product exists before updating or creating
    $product = Product::where('name', $request->product_name)->first();
    
    if ($product) {
        // Product exists, update stock if needed
        $product->stock += $request->quantity;
        $product->save();
    } else {
        // Product does not exist, do not create a new entry
        // Log or notify that the product was not created
        return redirect()->route('suppliers.index')->with('info', 'Product does not exist. No stock added.');
    }

    return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
}

    public function edit(Supplier $supplier)
    {
        return view('dashboard.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'kode_supplier' => 'required|string|max:255|unique:suppliers,kode_supplier,' . $id,
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'product_name' => 'required|string|max:255',
        'product_brand' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|integer|min:1',
        'harga_beli' => 'required|integer|min:1', // Validate harga_beli
        'expired' => 'required|date|after_or_equal:today',
        'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
    ]);

    $supplier = Supplier::findOrFail($id);

    $supplier->kode_supplier = $request->kode_supplier;
    $supplier->name = $request->name;
    $supplier->phone = $request->phone;
    $supplier->product_name = $request->product_name;
    $supplier->product_brand = $request->product_brand;
    $supplier->quantity = $request->quantity;
    $supplier->price = $request->price;
    $supplier->harga_beli = $request->harga_beli; // Update harga_beli
    $supplier->expired = \Carbon\Carbon::parse($request->expired)->format('Y-m-d');

    if ($request->hasFile('payment_proof')) {
        if ($supplier->payment_proof) {
            Storage::disk('public')->delete('photos/' . $supplier->payment_proof);
        }
        $paymentProofPath = $request->file('payment_proof')->store('photos', 'public');
        $supplier->payment_proof = basename($paymentProofPath); // Save only the filename
    }

    $supplier->save();

    return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
}

    public function destroy(Supplier $supplier)
    {
        if ($supplier->payment_proof) {
            Storage::delete('public/photos/' . $supplier->payment_proof);
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
