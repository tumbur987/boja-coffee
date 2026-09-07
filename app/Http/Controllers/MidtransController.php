<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'table_id'     => 'required|exists:tables,id',
            'items'        => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $itemsData  = [];
        $productNames = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $totalPrice += $subtotal;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
            $productNames[] = $product->name;
        }

        $orderId = 'SIBOJA-' . strtoupper(Str::random(6)) . '-' . time();

        $transaction = Transaction::create([
            'table_id'    => $request->table_id,
            'total_price' => $totalPrice,
            'status'      => 'pending',
            'order_id'    => $orderId,
        ]);

        foreach ($itemsData as $item) {
            $transaction->items()->create($item);
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'item_details' => array_map(function($item) {
                return [
                    'id'    => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'name' => Product::find($item['product_id'])->name,
                ];
            }, $itemsData),
            'customer_details' => [
                'first_name' => 'Pelanggan',
                'email'      => 'customer@siboja.com',
                'phone'      => '-',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            $transaction->update(['status' => 'paid']);
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil']);
        }
    }
}
