@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ Setting::get('site_name', 'SiBoja') }} &mdash; Meja {{ $table->number }}</title>
    @if(!empty(Setting::get('site_favicon')))
        <link rel="icon" href="{{ asset('storage/' . Setting::get('site_favicon')) }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --dark: #1a0e0a;
            --coffee: #4a2c2a;
            --coffee-light: #6b4226;
            --cream: #d4a574;
            --cream-light: #f5e6d3;
            --cream-lighter: #faf3eb;
            --bg: #f8f5f1;
            --white: #ffffff;
            --text: #2c1810;
            --text-light: #8b7355;
            --success: #22c55e;
            --danger: #ef4444;
            --shadow: 0 4px 16px rgba(44,24,16,0.06);
            --shadow-lg: 0 12px 40px rgba(44,24,16,0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); padding-bottom: 90px; }

        .header { background: linear-gradient(135deg, var(--dark), var(--coffee)); padding: 20px 20px 24px; color: white; }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .brand { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .brand span { font-size: 20px; }
        .brand strong { font-size: 17px; color: var(--cream); font-weight: 800; }
        .table-badge { background: var(--cream); color: var(--dark); padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; }
        .header-center { text-align: center; }
        .header-center h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .header-center p { font-size: 13px; opacity: 0.6; }

        .tabs-wrapper { padding: 12px 0 0; position: sticky; top: 0; z-index: 50; background: var(--bg); }
        .tabs { display: flex; gap: 8px; overflow-x: auto; padding: 0 16px 12px; -webkit-overflow-scrolling: touch; }
        .tabs::-webkit-scrollbar { display: none; }
        .tab { padding: 8px 18px; border-radius: 50px; border: 2px solid rgba(74,44,42,0.12); background: var(--white); color: var(--text-light); font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap; transition: all 0.2s; flex-shrink: 0; }
        .tab.active { background: var(--coffee); color: white; border-color: var(--coffee); box-shadow: 0 4px 12px rgba(74,44,42,0.2); }

        .menu-list { padding: 4px 16px; }
        .menu-card { background: var(--white); border-radius: 16px; padding: 16px; margin-bottom: 10px; box-shadow: var(--shadow); display: flex; justify-content: space-between; align-items: center; gap: 12px; border: 1px solid rgba(44,24,16,0.04); transition: all 0.2s; }
        .menu-card:hover { box-shadow: var(--shadow-lg); }
        .menu-left { flex: 1; min-width: 0; }
        .menu-emoji { font-size: 24px; margin-bottom: 4px; }
        .menu-name { font-size: 15px; font-weight: 700; color: var(--dark); margin-bottom: 2px; }
        .menu-cat { font-size: 11px; color: var(--text-light); font-weight: 500; margin-bottom: 6px; }
        .menu-price { font-size: 16px; font-weight: 800; color: var(--coffee); }
        .menu-right { flex-shrink: 0; }

        .qty-control { display: flex; align-items: center; gap: 0; background: var(--cream-lighter); border-radius: 12px; overflow: hidden; border: 2px solid rgba(74,44,42,0.08); }
        .qty-btn { width: 36px; height: 36px; border: none; background: transparent; color: var(--coffee); font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
        .qty-btn:hover { background: var(--cream); color: var(--dark); }
        .qty-num { width: 32px; text-align: center; font-weight: 800; font-size: 15px; color: var(--dark); }

        .empty { text-align: center; padding: 60px 20px; color: var(--text-light); }
        .empty i { font-size: 48px; opacity: 0.2; display: block; margin-bottom: 12px; }

        .cart-bar { position: fixed; bottom: 0; left: 0; right: 0; background: var(--white); border-top: 1px solid rgba(44,24,16,0.06); padding: 12px 16px; box-shadow: 0 -4px 20px rgba(44,24,16,0.08); display: none; z-index: 100; }
        .cart-inner { max-width: 600px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .cart-info { }
        .cart-count { font-size: 15px; font-weight: 700; color: var(--dark); }
        .cart-total { font-size: 13px; color: var(--text-light); }
        .cart-actions { display: flex; gap: 8px; align-items: center; }
        .cart-view-btn { background: transparent; color: var(--coffee); border: 2px solid var(--coffee); padding: 14px 18px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
        .cart-view-btn:hover { background: var(--cream-lighter); }
        .cart-btn { background: var(--coffee); color: white; border: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
        .cart-btn:hover { background: var(--dark); transform: translateY(-1px); }
        .cart-btn:disabled { background: #ccc; cursor: not-allowed; transform: none; }

        .success-overlay { position: fixed; inset: 0; background: rgba(26,14,10,0.9); display: none; align-items: center; justify-content: center; z-index: 200; }
        .success-overlay.show { display: flex; }
        .success-box { background: var(--white); border-radius: 24px; padding: 48px 36px; text-align: center; max-width: 360px; width: 90%; animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes popIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
        .success-icon { width: 80px; height: 80px; border-radius: 50%; background: rgba(34,197,94,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px; }
        .success-box h2 { font-size: 22px; font-weight: 800; color: var(--dark); margin-bottom: 8px; }
        .success-box p { font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 24px; }
        .success-box .table-code { display: inline-block; background: var(--cream-lighter); padding: 8px 20px; border-radius: 8px; font-weight: 800; font-size: 16px; color: var(--coffee); margin-bottom: 24px; }
        .success-btn { background: var(--coffee); color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; width: 100%; }

        .loading { text-align: center; padding: 40px; }
        .spinner { width: 36px; height: 36px; border: 3px solid var(--cream-light); border-top-color: var(--coffee); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 12px; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .drawer-overlay { position: fixed; inset: 0; background: rgba(26,14,10,0.5); display: none; z-index: 150; }
        .drawer-overlay.show { display: block; }
        .cart-drawer { position: fixed; bottom: 0; left: 0; right: 0; max-height: 80vh; background: var(--white); border-radius: 24px 24px 0 0; z-index: 160; transform: translateY(100%); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden; display: flex; flex-direction: column; }
        .cart-drawer.show { transform: translateY(0); }
        .drawer-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 20px 12px; border-bottom: 1px solid rgba(44,24,16,0.06); flex-shrink: 0; }
        .drawer-header h2 { font-size: 18px; font-weight: 800; color: var(--dark); }
        .drawer-close { background: none; border: none; font-size: 20px; color: var(--text-light); cursor: pointer; padding: 4px; }
        .drawer-body { overflow-y: auto; padding: 8px 20px 16px; flex: 1; }
        .drawer-footer { padding: 12px 20px 20px; border-top: 1px solid rgba(44,24,16,0.06); flex-shrink: 0; }
        .cart-item { display: flex; align-items: center; gap: 12px; padding: 14px 0; border-bottom: 1px solid rgba(44,24,16,0.04); }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name { font-size: 14px; font-weight: 700; color: var(--dark); }
        .cart-item-price { font-size: 13px; color: var(--text-light); margin-top: 2px; }
        .cart-item-subtotal { font-size: 13px; font-weight: 700; color: var(--coffee); text-align: right; }
        .cart-item-actions { display: flex; align-items: center; gap: 8px; }
        .cart-item-delete { background: none; border: none; color: var(--danger); font-size: 14px; cursor: pointer; padding: 6px; opacity: 0.6; transition: all 0.2s; }
        .cart-item-delete:hover { opacity: 1; }
        .drawer-summary { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .drawer-summary-label { font-size: 14px; color: var(--text-light); }
        .drawer-summary-total { font-size: 20px; font-weight: 800; color: var(--dark); }
        .drawer-clear-btn { background: none; border: none; color: var(--danger); font-size: 13px; font-weight: 600; cursor: pointer; padding: 4px 8px; opacity: 0.7; transition: all 0.2s; }
        .drawer-clear-btn:hover { opacity: 1; }
        .drawer-checkout-btn { background: var(--coffee); color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 700; font-size: 16px; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; }
        .drawer-checkout-btn:hover { background: var(--dark); }
        .drawer-checkout-btn:disabled { background: #ccc; cursor: not-allowed; }
        .drawer-empty { text-align: center; padding: 40px 20px; color: var(--text-light); }
        .drawer-empty i { font-size: 40px; opacity: 0.2; display: block; margin-bottom: 12px; }
        .drawer-name { margin-bottom: 12px; }
        .drawer-name-input { width: 100%; padding: 12px 14px; border: 2px solid rgba(74,44,42,0.12); border-radius: 10px; font-size: 14px; font-weight: 600; color: var(--dark); background: var(--cream-lighter); outline: none; transition: border-color 0.2s; }
        .drawer-name-input:focus { border-color: var(--coffee); }
        .drawer-name-input::placeholder { color: var(--text-light); font-weight: 400; }

        .cart-badge { position: absolute; top: -6px; right: -6px; background: var(--danger); color: white; font-size: 10px; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .cart-btn-wrapper { position: relative; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-top">
            <a href="/" class="brand">
                @if(!empty(Setting::get('site_logo')))
                    <img src="{{ asset('storage/' . Setting::get('site_logo')) }}" alt="Logo" style="height: 28px; border-radius: 6px;">
                @else
                    <span>&#9749;</span>
                @endif
                <strong>{{ Setting::get('site_name', 'SiBoja') }}</strong>
            </a>
            <div class="table-badge">
                <i class="fas fa-chair" style="margin-right:4px;"></i> Meja {{ $table->number }}
            </div>
        </div>
        <div class="header-center">
            <h1>Pesan Sekarang</h1>
            <p>Pilih menu favoritmu dan bayar langsung</p>
        </div>
    </div>

    <div class="tabs-wrapper">
        <div class="tabs" id="tabs">
            <div class="tab active" data-cat="all">Semua</div>
        </div>
    </div>

    <div class="menu-list" id="menuList">
        <div class="loading">
            <div class="spinner"></div>
            <p style="font-size:13px; color:var(--text-light);">Memuat menu...</p>
        </div>
    </div>

    <div class="cart-bar" id="cartBar">
        <div class="cart-inner">
            <div class="cart-info">
                <div class="cart-count" id="cartCount">0 item</div>
                <div class="cart-total" id="cartTotal">Rp0</div>
            </div>
            <div class="cart-actions">
                <button class="cart-view-btn" id="cartViewBtn">
                    <i class="fas fa-shopping-bag"></i>
                </button>
                <button class="cart-btn" id="cartBtn">
                    <i class="fas fa-shopping-bag"></i> Keranjang
                </button>
            </div>
        </div>
    </div>

    <div class="drawer-overlay" id="drawerOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="drawer-header">
            <h2>Keranjang Belanja</h2>
            <button class="drawer-close" id="drawerCloseBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="drawer-body" id="drawerBody">
            <div class="drawer-empty" id="drawerEmpty">
                <i class="fas fa-shopping-bag"></i>
                <p>Keranjang masih kosong</p>
            </div>
            <div id="drawerItems"></div>
        </div>
        <div class="drawer-footer" id="drawerFooter" style="display:none;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <button class="drawer-clear-btn" id="drawerClearBtn"><i class="fas fa-trash-alt"></i> Kosongkan</button>
            </div>
            <div class="drawer-name">
                <input type="text" id="customerName" class="drawer-name-input" placeholder="Nama Anda" maxlength="255" required>
            </div>
            <div class="drawer-summary">
                <span class="drawer-summary-label">Total</span>
                <span class="drawer-summary-total" id="drawerTotal">Rp0</span>
            </div>
            <button class="drawer-checkout-btn" id="drawerCheckoutBtn">
                <i class="fas fa-shopping-bag"></i> Pesan Sekarang
            </button>
        </div>
    </div>

    <div class="success-overlay" id="successOverlay">
        <div class="success-box">
            <div class="success-icon" id="successIcon">&#10003;</div>
            <h2 id="successTitle">Pembayaran Berhasil!</h2>
            <p id="successDesc">Pesanan Anda sedang diproses. Silakan tunggu di meja Anda.</p>
            <div class="table-code">Meja {{ $table->number }}</div>
            <br>
            <button class="success-btn" id="successBtn">Pesan Lagi</button>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    <div id="order-data"
         data-table-id="{{ $table->id }}"
         data-table-code="{{ $table->code }}"
         data-csrf="{{ csrf_token() }}"
         data-client-key="{{ config('midtrans.client_key') }}"
         style="display:none;"></div>
    <script src="{{ asset('js/order.js') }}"></script>
</body>
</html>
