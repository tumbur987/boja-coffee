<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['table', 'items.product'])->latest()->get();
        $tables   = Table::orderBy('number')->get();
        $products = Product::with('category')->orderBy('name')->get();
        return view('admin.transaction.index', compact('transactions', 'tables', 'products'));
    }

    public function create()
    {
        $tables    = Table::orderBy('number')->get();
        $products  = Product::with('category')->orderBy('name')->get();
        return view('admin.transaction.create', compact('tables', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'           => 'required|exists:tables,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $itemsData  = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price   = $product->price * $item['quantity'];
            $totalPrice += $price;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        $transaction = Transaction::create([
            'table_id'    => $request->table_id,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        foreach ($itemsData as $item) {
            $transaction->items()->create($item);
        }

        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load(['items.product', 'table']));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load(['items.product', 'table']);
        $tables   = Table::orderBy('number')->get();
        $products = Product::with('category')->orderBy('name')->get();
        return view('admin.transaction.edit', compact('transaction', 'tables', 'products'));
    }

    public function update(Request $request, Transaction $transaction)
    {
$request->validate([
            'table_id'           => 'required|exists:tables,id',
            'customer_name'      => 'required|string|max:255',
            'status'             => 'required|in:pending,lunas,cancelled',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $itemsData  = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price   = $product->price * $item['quantity'];
            $totalPrice += $price;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        $transaction->update([
            'table_id'       => $request->table_id,
            'customer_name'  => $request->customer_name,
            'total_price'    => $totalPrice,
            'status'         => $request->status,
        ]);

        $transaction->items()->delete();
        foreach ($itemsData as $item) {
            $transaction->items()->create($item);
        }

        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->items()->delete();
        $transaction->delete();
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
