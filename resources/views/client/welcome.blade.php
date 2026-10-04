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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(160deg, var(--dark) 0%, #2c1810 45%, var(--coffee) 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        body::before { content: ''; position: absolute; top: -20%; right: -30%; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, rgba(212,165,116,0.12), transparent 70%); }
        body::after { content: ''; position: absolute; bottom: -25%; left: -25%; width: 450px; height: 450px; border-radius: 50%; background: radial-gradient(circle, rgba(212,165,116,0.08), transparent 70%); }

        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 22px; position: relative; z-index: 2; }
        .brand { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .brand img { height: 30px; border-radius: 6px; }
        .brand span.emoji { font-size: 24px; }
        .brand strong { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--cream); font-weight: 800; }
        .table-badge { background: var(--cream); color: var(--dark); padding: 7px 16px; border-radius: 50px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; }

        .hero { flex: 1; display: flex; align-items: center; justify-content: center; padding: 8px 24px 32px; text-align: center; position: relative; z-index: 2; }
        .hero-inner { max-width: 440px; width: 100%; }
        .cup { width: 120px; height: 120px; margin: 0 auto 22px; border-radius: 50%; background: linear-gradient(135deg, rgba(212,165,116,0.22), rgba(212,165,116,0.06)); display: flex; align-items: center; justify-content: center; font-size: 56px; animation: float 4s ease-in-out infinite; border: 1px solid rgba(212,165,116,0.25); }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        .badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(212,165,116,0.12); border: 1px solid rgba(212,165,116,0.25); color: var(--cream); padding: 7px 16px; border-radius: 50px; font-size: 12px; font-weight: 600; margin-bottom: 18px; }
        h1 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 8vw, 38px); font-weight: 900; color: #fff; line-height: 1.15; margin-bottom: 12px; letter-spacing: -0.5px; }
        h1 em { font-style: normal; color: var(--cream); }
        .subtitle { font-size: 15px; color: rgba(255,255,255,0.75); line-height: 1.7; margin-bottom: 28px; }

        .actions { display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 16px 28px; border-radius: 16px; text-decoration: none; font-weight: 800; font-size: 16px; transition: all 0.25s; cursor: pointer; border: none; width: 100%; }
        .btn-primary { background: var(--cream); color: var(--dark); box-shadow: 0 8px 24px rgba(212,165,116,0.28); }
        .btn-primary:hover { background: var(--cream-light); transform: translateY(-2px); }
        .btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.25); }
        .btn-outline:hover { border-color: var(--cream); color: var(--cream); }

        .steps { display: flex; flex-direction: column; gap: 10px; text-align: left; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: 16px 18px; }
        .step { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.85); font-size: 13px; font-weight: 600; }
        .step-num { width: 26px; height: 26px; border-radius: 50%; background: rgba(212,165,116,0.2); color: var(--cream); font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        footer { text-align: center; padding: 0 24px 26px; position: relative; z-index: 2; }
        footer a { color: rgba(255,255,255,0.55); font-size: 12px; text-decoration: none; }
        footer a:hover { color: var(--cream); }
    </style>
</head>
<body>

    <div class="topbar">
        <a href="/" class="brand">
            @if(!empty(Setting::get('site_logo')))
                <img src="{{ asset('storage/' . Setting::get('site_logo')) }}" alt="Logo">
            @else
                <span class="emoji">&#9749;</span>
            @endif
            <strong>{{ Setting::get('site_name', 'SiBoja') }}</strong>
        </a>
        <span class="table-badge"><i class="fas fa-chair"></i> Meja {{ $table->number }}</span>
    </div>

    <main class="hero">
        <div class="hero-inner">
            <div class="cup">&#127861;</div>

            <div class="badge"><i class="fas fa-qrcode"></i> QR Code Meja {{ $table->number }} Terdeteksi</div>

            <h1>Selamat Datang di <em>{{ Setting::get('site_name', 'SiBoja Coffee') }}</em></h1>

            <p class="subtitle">
                Nikmati kopi favoritmu langsung dari meja. Pilih menu, bayar dengan mudah,
                dan tunggu pesananmu datang.
            </p>

            <div class="actions">
                <a href="{{ route('order.menu', $table->code) }}" class="btn btn-primary">
                    <i class="fas fa-mug-hot"></i> Pesan Sekarang
                </a>
                <a href="{{ route('order.menu', $table->code) }}" class="btn btn-outline">
                    <i class="fas fa-list"></i> Lihat Menu
                </a>
            </div>

            <div class="steps">
                <div class="step"><span class="step-num">1</span> Pilih menu favoritmu dan masukkan ke keranjang</div>
                <div class="step"><span class="step-num">2</span> Bayar dengan aman lewat QRIS / e-wallet</div>
                <div class="step"><span class="step-num">3</span> Pesanan diantar ke meja nomor {{ $table->number }}</div>
            </div>
        </div>
    </main>

    <footer>
        <a href="/">Butuh bantuan? Hubungi pelayan &middot; Lihat profil kami <i class="fas fa-arrow-right" style="font-size:10px;"></i></a>
    </footer>

</body>
</html>
