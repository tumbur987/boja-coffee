<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $this->syncPendingTransactions();

        // Transaksi 7 hari terakhir
        $dailyTransactions = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $chartLabels = $dailyTransactions->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $chartCounts = $dailyTransactions->pluck('total');
        $chartRevenue = $dailyTransactions->pluck('revenue');

        // Status transaksi
        $statusCounts = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Menu terlaris
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('transaction', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        $todayRevenue = Transaction::where('status', 'lunas')
            ->whereDate('created_at', today())
            ->sum('total_price');

        $todayCount = Transaction::whereDate('created_at', today())->count();

        $monthRevenue = Transaction::where('status', 'lunas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Pengunjung 30 hari terakhir (berdasarkan jumlah transaksi per hari)
        $dailyVisitors = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $visitorLabels = $dailyVisitors->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $visitorCounts = $dailyVisitors->pluck('total');

        // Produk stok habis
        $outOfStockProducts = Product::where('stock', '<=', 0)->get();

        // Produk stok hampir habis (1-9)
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<', 10)->get();

        return view('admin.dashboard.index', compact(
            'chartLabels', 'chartCounts', 'chartRevenue',
            'statusCounts', 'topProducts',
            'todayRevenue', 'todayCount', 'monthRevenue',
            'visitorLabels', 'visitorCounts',
            'outOfStockProducts', 'lowStockProducts'
        ));
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
                    ->timeout(10)
                    ->get($baseUrl.'/v2/'.$transaction->order_id.'/status');

                $body = $response->json();
                $transactionStatus = $body['transaction_status'] ?? null;
                $fraudStatus = $body['fraud_status'] ?? null;

                Log::info('sync pending', [
                    'order_id' => $transaction->order_id,
                    'http_code' => $response->status(),
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                ]);

                if (! $response->successful()) {
                    continue;
                }

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
