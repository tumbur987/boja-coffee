<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();
        return view('admin.product.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255|unique:products,name',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.unique' => 'Nama produk sudah ada. Silakan gunakan nama lain.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image_key']);

        Product::create(collect($validated)->except(['image'])->toArray());
        if ($request->hasFile('image')) {
            Product::latest()->first()->update(['image' => $validated['image']]);
        }

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255|unique:products,name,' . $product->id,
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.unique' => 'Nama produk sudah ada. Silakan gunakan nama lain.',
        ]);

        $data = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus.');
    }
}
