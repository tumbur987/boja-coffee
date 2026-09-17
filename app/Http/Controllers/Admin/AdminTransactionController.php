<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Table;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $this->syncPendingTransactions();
        $transactions = Transaction::with(['table', 'items.product'])->latest()->paginate(10);
        $tables = Table::orderBy('number')->get();
        $products = Product::with('category')->orderBy('name')->get();

        return view('admin.transaction.index', compact('transactions', 'tables', 'products'));
    }

    public function create()
    {
        $tables = Table::orderBy('number')->get();
        $products = Product::with('category')->orderBy('name')->get();

        return view('admin.transaction.create', compact('tables', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:50',
        ]);

        $totalPrice = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->price * $item['quantity'];
            $totalPrice += $price;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        $transaction = Transaction::create([
            'table_id' => $request->table_id,
            'total_price' => $totalPrice,
            'status' => 'pending',
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
        $tables = Table::orderBy('number')->get();
        $products = Product::with('category')->orderBy('name')->get();

        return view('admin.transaction.edit', compact('transaction', 'tables', 'products'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'status' => 'required|in:pending,lunas,selesai,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:50',
        ]);

        $totalPrice = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->price * $item['quantity'];
            $totalPrice += $price;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        $transaction->update([
            'table_id' => $request->table_id,
            'customer_name' => $request->customer_name,
            'total_price' => $totalPrice,
            'status' => $request->status,
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

    public function markSelesai(Transaction $transaction)
    {
        $transaction->update(['status' => 'selesai']);
        $this->deductStock($transaction);

        return redirect()->route('transaction.index')->with('success', 'Pesanan ditandai selesai.');
    }

    private function syncPendingTransactions()
    {
        $pendingTransactions = Transaction::where('status', 'pending')
            ->whereNotNull('order_id')
            ->where('created_at', '>=', now()->subHours(24))
            ->get();

        if ($pendingTransactions->isEmpty()) {
            return;
        }

        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production', false);
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        foreach ($pendingTransactions as $transaction) {
            try {
                $response = Http::withBasicAuth($serverKey, '')
                    ->get($baseUrl.'/v2/'.$transaction->order_id.'/status');

                if (! $response->successful()) {
                    continue;
                }

                $body = $response->json();
                $transactionStatus = $body['transaction_status'] ?? null;
                $fraudStatus = $body['fraud_status'] ?? null;

                if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {
                    $transaction->update(['status' => 'lunas']);
                    Log::info('sync: transaksi lunas', ['order_id' => $transaction->order_id]);
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $transaction->update(['status' => 'cancelled']);
                    Log::info('sync: transaksi dibatalkan', ['order_id' => $transaction->order_id]);
                }
            } catch (\Exception $e) {
                Log::error('sync: gagal cek', ['order_id' => $transaction->order_id, 'error' => $e->getMessage()]);
            }
        }
    }

    private function deductStock(Transaction $transaction)
    {
        $transaction->load('items.product');
        foreach ($transaction->items as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
                Log::info('stock dikurangi (admin)', [
                    'product' => $item->product->name,
                    'qty' => $item->quantity,
                    'sisa' => $item->product->stock,
                ]);
            }
        }
    }
}
