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
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Transaksi Hari Ini</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">{{ $todayCount }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--cream-lighter); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-receipt" style="font-size: 20px; color: var(--cream);"></i>
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
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Pendapatan Hari Ini</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(34,197,94,0.08); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-money-bill-wave" style="font-size: 20px; color: var(--success);"></i>
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
                        <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Pendapatan Bulan Ini</p>
                        <h3 style="font-size: 26px; font-weight: 800; color: var(--coffee-dark); margin: 4px 0 0;">Rp{{ number_format($monthRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59,130,246,0.08); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check" style="font-size: 20px; color: var(--info);"></i>
                    </div>
                </div>
            </div>
        </div>
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
                                <a href="{{ route('admin.product.index') }}" class="btn btn-sm" style="background: var(--coffee); color: white; border-radius: 8px; font-weight: 600; font-size: 12px;">
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
            <div class="card-body">
                <canvas id="transactionChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: 800;">Status Transaksi</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
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
            <div class="card-body">
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
            <div class="card-body">
                <canvas id="topProductsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

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

    const coffeeDark = '#2c1810';
    const coffee = '#4a2c2a';
    const coffeeLight = '#6b4226';
    const cream = '#d4a574';
    const success = '#22c55e';
    const warning = '#f59e0b';
    const danger = '#ef4444';
    const info = '#3b82f6';

    // Line chart - Transaksi 7 hari
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

    // Doughnut chart - Status
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
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 16, font: { weight: '600', size: 13 } }
                }
            }
        }
    });

    // Bar chart - Top Products
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

    // Bar chart - Pengunjung 30 hari
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
