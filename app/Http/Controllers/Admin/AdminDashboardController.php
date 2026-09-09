<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
     public function index()
    {
        $this->syncPendingTransactions();
        return view('admin.dashboard.index');
    }

    private function syncPendingTransactions()
    {
        $pendingTransactions = Transaction::where('status', 'pending')
            ->whereNotNull('order_id')
            ->where('created_at', '>=', now()->subHours(24))
            ->get();

        if ($pendingTransactions->isEmpty()) return;

        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production', false);
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        foreach ($pendingTransactions as $transaction) {
            try {
                $response = Http::withBasicAuth($serverKey, '')
                    ->timeout(10)
                    ->get($baseUrl . '/v2/' . $transaction->order_id . '/status');

                $body = $response->json();
                $transactionStatus = $body['transaction_status'] ?? null;
                $fraudStatus = $body['fraud_status'] ?? null;

                Log::info('sync pending', [
                    'order_id' => $transaction->order_id,
                    'http_code' => $response->status(),
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                ]);

                if (!$response->successful()) continue;

                if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {
                    $transaction->update(['status' => 'lunas']);
                    Log::info('sync: lunas', ['order_id' => $transaction->order_id]);
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $transaction->update(['status' => 'cancelled']);
                }
            } catch (\Exception $e) {
                Log::error('dashboard sync: gagal cek', ['order_id' => $transaction->order_id, 'error' => $e->getMessage()]);
            }
        }
    }
}
