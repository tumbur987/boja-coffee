@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card" style="border-left: 4px solid var(--coffee);">
            <div class="card-body" style="padding: 18px 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Total Produk</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ \App\Models\Product::count() }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--cream-lighter); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coffee" style="font-size: 20px; color: var(--coffee);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card" style="border-left: 4px solid var(--cream);">
            <div class="card-body" style="padding: 18px 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Kategori</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ \App\Models\Category::count() }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--cream-lighter); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tags" style="font-size: 20px; color: var(--cream);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card" style="border-left: 4px solid var(--info);">
            <div class="card-body" style="padding: 18px 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Meja</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ \App\Models\Table::count() }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59,130,246,0.08); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chair" style="font-size: 20px; color: var(--info);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card" style="border-left: 4px solid var(--success);">
            <div class="card-body" style="padding: 18px 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Transaksi</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ \App\Models\Transaction::count() }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(34,197,94,0.08); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-receipt" style="font-size: 20px; color: var(--success);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;">Transaksi Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Meja</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (\App\Models\Transaction::with('table')->latest()->take(5)->get() as $trx)
                        <tr>
                            <td><span class="badge badge-primary">Meja {{ $trx->table->number }}</span></td>
                            <td style="font-weight: 600;">{{ $trx->customer_name ?? '-' }}</td>
                            <td style="font-weight: 700;">Rp.{{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if ($trx->status == 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif ($trx->status == 'selesai')
                                    <span class="badge badge-info">Selesai</span>
                                @elseif ($trx->status == 'cancelled')
                                    <span class="badge badge-danger">Batal</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ $trx->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;">Menu Populer</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (\App\Models\Product::with('category')->orderBy('name')->take(5)->get() as $product)
                        <tr>
                            <td style="font-weight: 600;">{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td style="font-weight: 700; color: var(--coffee);">Rp.{{ number_format($product->price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: var(--text-muted);">Belum ada produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
