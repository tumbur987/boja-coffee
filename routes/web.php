<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Models\Transaction;


Route::get('/', [HomeController::class, 'index']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('category', AdminCategoryController::class);
    Route::resource('product', AdminProductController::class);
    Route::resource('table', AdminTableController::class);
    Route::resource('transaction', AdminTransactionController::class);
    Route::post('transaction/{transaction}/selesai', [AdminTransactionController::class, 'markSelesai'])->name('transaction.selesai');
    Route::resource('user', AdminUserController::class);

    Route::get('setting', [AdminSettingController::class, 'index'])->name('setting.index');
    Route::put('setting', [AdminSettingController::class, 'update'])->name('setting.update');

    Route::get('table/{table}/qr-download', [AdminTableController::class, 'qrDownload'])->name('table.qr-download');
    Route::get('table/{table}/qr-preview', [AdminTableController::class, 'qrPreview'])->name('table.qr-preview');
});

// Client order page (no auth)
Route::get('/order/{code}', [OrderController::class, 'index'])->name('order.index');

// API for client menu
Route::get('/api/menu', function () {
    return response()->json(Product::with('category')->get());
});

// API for client order status check
Route::get('/api/order-status/{orderId}', function ($orderId) {
    $transaction = Transaction::where('order_id', $orderId)->first();
    if (!$transaction) {
        return response()->json(['status' => 'not_found'], 404);
    }
    return response()->json(['status' => $transaction->status]);
});

// API for client order history
Route::get('/api/order-history/{tableId}', function ($tableId) {
    $customerName = request('customer_name');
    $since = request('since');
    $query = Transaction::with(['items.product', 'table'])
        ->where('table_id', $tableId)
        ->latest();

    if ($customerName) {
        $query->where('customer_name', $customerName);
    }

    if ($since) {
        $query->where('created_at', '>=', $since);
    }

    $transactions = $query->limit(20)->get()->map(function ($t) {
        return [
            'id' => $t->id,
            'order_id' => $t->order_id,
            'customer_name' => $t->customer_name,
            'total_price' => $t->total_price,
            'status' => $t->status,
            'created_at' => $t->created_at->format('d M Y, H:i'),
            'items' => $t->items->map(function ($item) {
                return [
                    'name' => $item->product->name ?? '-',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
        ];
    });

    return response()->json($transactions);
});

require __DIR__.'/auth.php';

Route::post('/midtrans/create', [MidtransController::class, 'createTransaction']);
Route::post('/midtrans/payment-status', [MidtransController::class, 'paymentStatus']);
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
