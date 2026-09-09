<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'table_id'        => 'required|exists:tables,id',
            'customer_name'   => 'required|string|max:255',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $itemsData  = [];
        $itemDetails = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $totalPrice += $product->price * $item['quantity'];
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
            $itemDetails[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $item['quantity'],
            ];
        }

        $orderId = 'SIBOJA-' . strtoupper(Str::random(6)) . '-' . time();

        $transaction = Transaction::create([
            'table_id'       => $request->table_id,
            'customer_name'  => $request->customer_name,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'order_id'       => $orderId,
        ]);

        foreach ($itemsData as $item) {
            $transaction->items()->create($item);
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => 'customer@siboja.com',
                'phone'      => '-',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken, 'order_id' => $orderId]);
        } catch (\Exception $e) {
            $transaction->items()->delete();
            $transaction->delete();
            Log::error('Midtrans Snap gagal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran. Silakan coba lagi.',
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $serverKey  = config('midtrans.server_key');
        $signature  = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if (!hash_equals($signature, (string) $request->signature_key)) {
            return response()->json(['status' => 'invalid signature'], 403);
        }

        $transaction = Transaction::where('order_id', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['status' => 'order not found'], 404);
        }

        if ((int) $request->gross_amount !== (int) $transaction->total_price) {
            return response()->json(['status' => 'amount mismatch'], 400);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus       = $request->fraud_status;

        if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {
            $transaction->update(['status' => 'lunas']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->update(['status' => 'cancelled']);
        }

        return response()->json(['status' => 'ok']);
    }

    public function paymentStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        Log::info('payment-status dipanggil', ['order_id' => $orderId, 'ip' => $request->ip()]);

        $request->validate([
            'order_id' => 'required|string',
        ]);

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::warning('payment-status: transaksi tidak ditemukan', ['order_id' => $orderId]);
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if ($transaction->status === 'lunas') {
            Log::info('payment-status: sudah lunas', ['order_id' => $orderId]);
            return response()->json(['success' => true, 'status' => 'lunas']);
        }

        try {
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production', false);
            $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

            $response = Http::withBasicAuth($serverKey, '')
                ->get($baseUrl . '/v2/' . $orderId . '/status');

            $body = $response->json();
            $transactionStatus = $body['transaction_status'] ?? null;
            $fraudStatus = $body['fraud_status'] ?? null;

            Log::info('payment-status: midtrans response', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {
                $transaction->update(['status' => 'lunas']);
                Log::info('payment-status: BERHASIL lunas', ['order_id' => $orderId]);
                return response()->json(['success' => true, 'status' => 'lunas']);
            }
        } catch (\Exception $e) {
            Log::error('payment-status: gagal cek midtrans', ['order_id' => $orderId, 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => false, 'status' => $transaction->status]);
    }
}