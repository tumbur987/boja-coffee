@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="{{ route('product.index') }}" class="text-decoration-none" style="display:block;">
            <div class="card dashboard-stat-card" style="border-left: 4px solid var(--coffee); cursor: pointer;">
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
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="{{ route('transaction.index', ['date_from' => now()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="text-decoration-none" style="display:block;">
            <div class="card dashboard-stat-card" style="border-left: 4px solid var(--cream); cursor: pointer;">
                <div class="card-body" style="padding: 18px 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Transaksi Hari Ini</p>
                            <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ $todayCount }}</h3>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--cream-lighter); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-receipt" style="font-size: 20px; color: var(--cream);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="{{ route('transaction.index', ['status' => 'lunas', 'date_from' => now()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="text-decoration-none" style="display:block;">
            <div class="card dashboard-stat-card" style="border-left: 4px solid var(--success); cursor: pointer;">
                <div class="card-body" style="padding: 18px 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Pendapatan Hari Ini</p>
                            <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(34,197,94,0.08); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-money-bill-wave" style="font-size: 20px; color: var(--success);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="{{ route('transaction.index', ['status' => 'lunas', 'date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->endOfMonth()->format('Y-m-d')]) }}" class="text-decoration-none" style="display:block;">
            <div class="card dashboard-stat-card" style="border-left: 4px solid var(--info); cursor: pointer;">
                <div class="card-body" style="padding: 18px 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Pendapatan Bulan Ini</p>
                            <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">Rp{{ number_format($monthRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59,130,246,0.08); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-check" style="font-size: 20px; color: var(--info);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

@if($outOfStockProducts->count() > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card" style="border-left: 4px solid #ef4444;">
            <div class="card-header" style="background: rgba(239,68,68,0.04);">
                <h5 class="card-title" style="font-weight: 800; color: #ef4444; margin: 0;">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Stok Habis — Perlu Restock
                    <span class="badge badge-danger ml-2" style="font-size: 12px;">{{ $outOfStockProducts->count() }} produk</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outOfStockProducts as $product)
                        <tr>
                            <td style="font-weight: 700;">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td style="font-weight: 600;">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-danger" style="font-size: 12px; padding: 5px 10px;">Stok: {{ $product->stock }}</span>
                            </td>
                            <td>
                                <a href="{{ route('product.index') }}" class="btn btn-sm" style="background: var(--coffee); color: white; border-radius: 8px; font-weight: 600; font-size: 12px;">
                                    <i class="fas fa-edit mr-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@if($lowStockProducts->count() > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-header" style="background: rgba(245,158,11,0.04);">
                <h5 class="card-title" style="font-weight: 800; color: #d97706; margin: 0;">
                    <i class="fas fa-exclamation-circle mr-1"></i> Stok Hampir Habis — Segera Restock
                    <span class="badge badge-warning ml-2" style="font-size: 12px;">{{ $lowStockProducts->count() }} produk</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Sisa Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td style="font-weight: 700;">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td style="font-weight: 600;">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-warning" style="font-size: 12px; padding: 5px 10px;">Stok: {{ $product->stock }}</span>
                            </td>
                            <td>
                                <a href="{{ route('product.index') }}" class="btn btn-sm" style="background: #f59e0b; color: white; border-radius: 8px; font-weight: 600; font-size: 12px;">
                                    <i class="fas fa-edit mr-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;">Transaksi 7 Hari Terakhir</h5>
            </div>
            <div class="card-body chart-clickable">
                <canvas id="transactionChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;">Status Transaksi</h5>
            </div>
            <div class="card-body d-flex justify-content-center chart-clickable">
                <canvas id="statusChart" width="280" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;"><i class="fas fa-users mr-1" style="color: var(--coffee);"></i> Jumlah Pengunjung (30 Hari Terakhir)</h5>
            </div>
            <div class="card-body chart-clickable">
                <canvas id="visitorChart" height="80"></canvas>
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
                            <td style="font-weight: 700;">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</td>
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
                <h5 class="card-title" style="font-weight: 800;">Menu Terlaris</h5>
            </div>
            <div class="card-body chart-clickable">
                <canvas id="topProductsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .dashboard-stat-card { transition: all 0.2s ease; }
    .dashboard-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(44,24,16,0.12) !important; }
    .chart-clickable { cursor: pointer; }
</style>
@endsection

@section('scripts')
<script>
    const labels = {!! json_encode($chartLabels) !!};
    const counts = {!! json_encode($chartCounts->toArray()) !!};
    const revenue = {!! json_encode($chartRevenue->toArray()) !!};
    const statusData = {!! json_encode($statusCounts) !!};
    const topProducts = {!! json_encode($topProducts->map(fn($tp) => ['name' => $tp->product->name ?? '-', 'qty' => $tp->total_qty])) !!};
    const visitorLabels = {!! json_encode($visitorLabels) !!};
    const visitorCounts = {!! json_encode($visitorCounts->toArray()) !!};
    const rawDates = {!! json_encode($dailyTransactions->pluck('date')->toArray()) !!};
    const rawVisitorDates = {!! json_encode($dailyVisitors->pluck('date')->toArray()) !!};

    const coffeeDark = '#2c1810';
    const coffee = '#4a2c2a';
    const coffeeLight = '#6b4226';
    const cream = '#d4a574';
    const success = '#22c55e';
    const warning = '#f59e0b';
    const danger = '#ef4444';
    const info = '#3b82f6';

    // Line chart - Transaksi 7 hari (klik per hari)
    new Chart(document.getElementById('transactionChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: counts,
                borderColor: coffee,
                backgroundColor: 'rgba(74,44,42,0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: coffee,
                pointRadius: 5,
                pointHoverRadius: 7,
                yAxisID: 'y'
            }, {
                label: 'Pendapatan (Rp)',
                data: revenue,
                borderColor: cream,
                backgroundColor: 'rgba(212,165,116,0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: cream,
                pointRadius: 5,
                pointHoverRadius: 7,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            onClick: function(evt) {
                var points = evt.getElementsAtEventForMode(evt.native, 'index', { intersect: false }, true);
                if (points.length > 0) {
                    var idx = points[0].index;
                    var date = rawDates[idx];
                    window.location.href = '{{ url("transaction") }}?date_from=' + date + '&date_to=' + date;
                }
            },
            plugins: {
                legend: { labels: { usePointStyle: true, font: { weight: '600' } } },
                tooltip: {
                    backgroundColor: coffeeDark,
                    titleFont: { weight: '700' },
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.datasetIndex === 1) return 'Pendapatan: Rp' + ctx.raw.toLocaleString('id-ID');
                            return 'Transaksi: ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Jumlah Transaksi', font: { weight: '600' } },
                    grid: { color: 'rgba(44,24,16,0.05)' },
                    ticks: { font: { weight: '600' } }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Pendapatan (Rp)', font: { weight: '600' } },
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { weight: '600' },
                        callback: val => 'Rp' + val.toLocaleString('id-ID')
                    }
                },
                x: { grid: { display: false }, ticks: { font: { weight: '600' } } }
            }
        }
    });

    // Doughnut chart - Status (klik per segmen)
    const statusLabels = Object.keys(statusData);
    const statusValues = Object.values(statusData);
    const statusColors = statusLabels.map(s => {
        if (s === 'lunas') return success;
        if (s === 'selesai') return info;
        if (s === 'cancelled') return danger;
        return warning;
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: statusValues,
                backgroundColor: statusColors,
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: false,
            cutout: '65%',
            onClick: function(evt) {
                var points = evt.getElementsAtEventForMode(evt.native, 'nearest', { intersect: true }, true);
                if (points.length > 0) {
                    var idx = points[0].index;
                    var status = statusLabels[idx];
                    window.location.href = '{{ url("transaction") }}?status=' + status;
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 16, font: { weight: '600', size: 13 } }
                }
            }
        }
    });

    // Bar chart - Top Products (klik ke halaman produk)
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: topProducts.map(p => p.name),
            datasets: [{
                label: 'Terjual',
                data: topProducts.map(p => p.qty),
                backgroundColor: [coffeeDark, coffee, coffeeLight, cream, '#e8c9a0'],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            onClick: function() {
                window.location.href = '{{ route("product.index") }}';
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: coffeeDark,
                    callbacks: { label: ctx => 'Terjual: ' + ctx.raw + 'x' }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(44,24,16,0.05)' }, ticks: { font: { weight: '600' } } },
                y: { grid: { display: false }, ticks: { font: { weight: '600', size: 12 } } }
            }
        }
    });

    // Bar chart - Pengunjung 30 hari (klik per hari)
    new Chart(document.getElementById('visitorChart'), {
        type: 'bar',
        data: {
            labels: visitorLabels,
            datasets: [{
                label: 'Pengunjung',
                data: visitorCounts,
                backgroundColor: function(ctx) {
                    const chart = ctx.chart;
                    const {ctx: canvasCtx, chartArea} = chart;
                    if (!chartArea) return coffee;
                    const gradient = canvasCtx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(74,44,42,0.4)');
                    gradient.addColorStop(1, coffee);
                    return gradient;
                },
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 24
            }]
        },
        options: {
            responsive: true,
            onClick: function(evt) {
                var points = evt.getElementsAtEventForMode(evt.native, 'index', { intersect: false }, true);
                if (points.length > 0) {
                    var idx = points[0].index;
                    var date = rawVisitorDates[idx];
                    window.location.href = '{{ url("transaction") }}?date_from=' + date + '&date_to=' + date;
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: coffeeDark,
                    titleFont: { weight: '700' },
                    callbacks: {
                        label: ctx => ctx.raw + ' pengunjung'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(44,24,16,0.05)' },
                    ticks: { font: { weight: '600' }, stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '600', size: 11 }, maxRotation: 45, autoSkip: true, maxTicksLimit: 15 }
                }
            }
        }
    });
</script>
@endsection
