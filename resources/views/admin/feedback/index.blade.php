@extends('layouts.admin.app')

@section('title', 'Feedback Pelanggan')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--coffee);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Total Feedback</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;">{{ $total }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--warning);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Rata-rata Rating</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;">
                        {{ $average }} <small style="font-size:14px; color:var(--text-muted); font-weight:600;">/ 5</small>
                    </h3>
                    <div style="font-size:14px; margin-top:2px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="color: {{ $i <= round($average) ? '#f59e0b' : '#e5e0d8' }};"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--success);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Pelanggan Puas (4-5 Bintang)</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;">{{ $satisfiedPercent }}%</h3>
                    <small style="font-size:12px; color:var(--text-muted);">{{ $satisfied }} dari {{ $total }} feedback</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--info);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Komentar Masuk</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;">{{ $withComment }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2" style="color:var(--coffee);"></i>Sebaran Rating</h3>
                </div>
                <div class="card-body">
                    @for ($i = 5; $i >= 1; $i--)
                        @php $count = $distribution[$i] ?? 0; @endphp
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size:13px; font-weight:700; width:52px; color:var(--text-muted);">{{ $i }} <i class="fas fa-star" style="color:#f59e0b; font-size:11px;"></i></span>
                            <div style="flex:1; height:10px; background:var(--cream-lighter); border-radius:10px; overflow:hidden;">
                                <div style="height:100%; width:{{ $total ? round($count / $total * 100) : 0 }}%; background:var(--coffee); border-radius:10px;"></div>
                            </div>
                            <span style="font-size:13px; font-weight:700; width:40px; text-align:right; color:var(--coffee-dark);">{{ $count }}</span>
                        </div>
                    @endfor
                    @if ($total === 0)
                        <p class="text-center mb-0" style="font-size:13px; color:var(--text-muted); padding:20px 0;">
                            <i class="fas fa-inbox" style="font-size:26px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                            Belum ada feedback
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-star mr-2" style="color:var(--warning);"></i>Daftar Feedback</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex flex-wrap" style="gap:8px;">
                        <a href="{{ route('feedback.index') }}" class="btn btn-sm {{ $ratingFilter === null ? 'btn-dark' : 'btn-outline-dark' }}">
                            <i class="fas fa-list mr-1"></i> Semua
                        </a>
                        @for ($i = 5; $i >= 1; $i--)
                            <a href="{{ route('feedback.index', ['rating' => $i]) }}" class="btn btn-sm {{ $ratingFilter === $i ? 'btn-warning' : 'btn-outline-warning' }}">
                                {{ $i }} <i class="fas fa-star" style="font-size:10px;"></i>
                            </a>
                        @endfor
                        @if ($ratingFilter)
                            <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th>Tanggal</th>
                                <th>Meja</th>
                                <th>Pelanggan</th>
                                <th style="width:110px;">Rating</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($feedbacks as $feedback)
                                <tr>
                                    <td>{{ ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration }}</td>
                                    <td style="white-space:nowrap; font-size:13px;">
                                        <div style="font-weight:600;">{{ $feedback->created_at->format('d/m/Y') }}</div>
                                        <div style="color:var(--text-muted);">{{ $feedback->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td><span class="badge badge-primary">Meja {{ $feedback->transaction->table->number ?? '-' }}</span></td>
                                    <td style="font-weight:600;">{{ $feedback->transaction->customer_name ?? '-' }}</td>
                                    <td style="white-space:nowrap;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star" style="color: {{ $i <= $feedback->rating ? '#f59e0b' : '#e5e0d8' }}; font-size:13px;"></i>
                                        @endfor
                                    </td>
                                    <td style="font-size:13px;">{{ $feedback->comment ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:30px; color:var(--text-muted);">
                                        <i class="fas fa-inbox" style="font-size:32px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                                        {{ $ratingFilter ? 'Tidak ada feedback dengan rating tersebut' : 'Belum ada feedback dari pelanggan' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($feedbacks->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrap">
                            <small class="text-muted" style="font-size:13px;">Menampilkan {{ $feedbacks->firstItem() }}-{{ $feedbacks->lastItem() }} dari {{ $feedbacks->total() }} data</small>
                            {{ $feedbacks->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
