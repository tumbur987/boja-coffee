<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link text-center" style="background: rgba(0,0,0,0.15); padding: 16px 20px;">
        @if(!empty(\App\Models\Setting::get('site_logo')))
            <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: 34px; border-radius: 8px;">
        @else
            <span style="font-size: 28px;">☕</span>
        @endif
        <span class="brand-text font-weight-800" style="color: #d4a574;">{{ \App\Models\Setting::get('site_name', 'SiBoja Coffee') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="margin: 16px 14px !important; padding-bottom: 14px !important; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div class="image">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--cream); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--coffee-dark); font-size: 14px;">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
            <div class="info" style="margin-left: 10px;">
                <a href="{{ route('profile.edit') }}" class="d-block" style="font-weight: 600; font-size: 13px;">{{ Auth::user()->name ?? 'Admin' }}</a>
                <small style="color: rgba(255,255,255,0.45); font-size: 11px;">{{ ucfirst(Auth::user()->role ?? 'admin') }}</small>
            </div>
        </div>

        <nav class="mt-2" style="padding: 0 8px;">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header" style="margin-top: 12px;">MENU UTAMA</li>

                <li class="nav-item">
                    <a href="{{ route('product.index') }}" class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coffee"></i>
                        <p>Kelola Produk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('category.index') }}" class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Kelola Kategori</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('table.index') }}" class="nav-link {{ request()->routeIs('table.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chair"></i>
                        <p>Kelola Meja</p>
                    </a>
                </li>

                <li class="nav-header" style="margin-top: 12px;">KEUANGAN</li>

                <li class="nav-item">
                    <a href="{{ route('transaction.index') }}" class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <li class="nav-header" style="margin-top: 12px;">PENGATURAN</li>

                <li class="nav-item">
                    <a href="{{ route('setting.index') }}" class="nav-link {{ request()->routeIs('setting.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Pengaturan Website</p>
                    </a>
                </li>

                @if(Auth::check() && Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Kelola User</p>
                    </a>
                </li>
                @endif

                <li class="nav-item" style="margin-top: 8px;">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
