@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Setting::get('site_name', 'SiBoja Coffee') }} &mdash; {{ Setting::get('meta_title', 'Nikmati Setiap Tegukan') }}</title>
    @if(!empty(Setting::get('site_favicon')))
        <link rel="icon" href="{{ asset('storage/' . Setting::get('site_favicon')) }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --dark: #1a0e0a;
            --coffee: #4a2c2a;
            --coffee-light: #6b4226;
            --cream: #d4a574;
            --cream-light: #f5e6d3;
            --cream-lighter: #faf3eb;
            --bg: #fdfbf9;
            --white: #ffffff;
            --text: #2c1810;
            --text-light: #6b5240;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); background: var(--bg); overflow-x: hidden; }

        /* ===== NAVBAR ===== */
        .nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 16px 0; transition: all 0.3s; }
        .nav.scrolled { background: rgba(26,14,10,0.95); backdrop-filter: blur(20px); padding: 10px 0; box-shadow: 0 4px 30px rgba(0,0,0,0.15); }
        .nav .container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-brand span.emoji { font-size: 28px; }
        .nav-brand span.text { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 800; color: var(--cream); letter-spacing: -0.5px; }
        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a { color: rgba(255,255,255,0.92); text-decoration: none; font-weight: 600; font-size: 15px; transition: color 0.2s; }
        .nav-links a:hover { color: var(--cream); }
        .nav-cta { background: var(--cream) !important; color: var(--dark) !important; padding: 10px 24px !important; border-radius: 50px !important; font-weight: 700 !important; }
        .nav-cta:hover { background: var(--cream-light) !important; transform: translateY(-1px); }

        /* ===== HERO ===== */
        .hero { min-height: 100vh; display: flex; align-items: center; background: linear-gradient(135deg, var(--dark) 0%, #2c1810 40%, var(--coffee) 100%); position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 800px; height: 800px; border-radius: 50%; background: radial-gradient(circle, rgba(212,165,116,0.08) 0%, transparent 70%); }
        .hero::after { content: ''; position: absolute; bottom: -30%; left: -10%; width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, rgba(212,165,116,0.05) 0%, transparent 70%); }
        .hero .container { max-width: 1200px; margin: 0 auto; padding: 120px 24px 80px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; position: relative; z-index: 2; }
        .hero-content { }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(212,165,116,0.12); border: 1px solid rgba(212,165,116,0.2); padding: 8px 18px; border-radius: 50px; color: var(--cream); font-size: 13px; font-weight: 600; margin-bottom: 24px; }
        .hero-badge i { font-size: 12px; }
        .hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(36px, 5vw, 64px); font-weight: 900; color: white; line-height: 1.1; margin-bottom: 20px; letter-spacing: -1px; }
        .hero h1 em { font-style: normal; color: var(--cream); }
        .hero p { font-size: 18px; color: rgba(255,255,255,0.8); line-height: 1.8; margin-bottom: 36px; max-width: 480px; }
        .hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-primary { display: inline-flex; align-items: center; gap: 10px; background: var(--cream); color: var(--dark); padding: 14px 32px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s; border: none; cursor: pointer; }
        .btn-primary:hover { background: var(--cream-light); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(212,165,116,0.3); }
        .btn-outline { display: inline-flex; align-items: center; gap: 10px; background: transparent; color: white; padding: 14px 32px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 15px; border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s; }
        .btn-outline:hover { border-color: var(--cream); color: var(--cream); }
        .hero-visual { position: relative; display: flex; justify-content: center; align-items: center; }
        .hero-cup { width: 320px; height: 320px; background: linear-gradient(135deg, rgba(212,165,116,0.15), rgba(212,165,116,0.05)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 140px; animation: float 4s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .hero-stats { display: flex; gap: 40px; margin-top: 48px; }
        .hero-stat h3 { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 800; color: var(--cream); }
        .hero-stat p { font-size: 14px; color: rgba(255,255,255,0.65); margin: 0; }

        /* ===== FEATURES ===== */
        .features { padding: 100px 0; background: var(--bg); }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header .label { display: inline-flex; align-items: center; gap: 8px; background: rgba(74,44,42,0.08); padding: 8px 18px; border-radius: 50px; color: var(--coffee); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .section-header h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3.5vw, 42px); font-weight: 800; color: var(--dark); line-height: 1.2; }
        .section-header p { color: var(--text-light); font-size: 18px; line-height: 1.7; margin-top: 12px; max-width: 500px; margin-left: auto; margin-right: auto; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .feature-card { background: var(--white); border-radius: 20px; padding: 36px 28px; box-shadow: 0 4px 20px rgba(44,24,16,0.04); transition: all 0.3s; border: 1px solid rgba(44,24,16,0.04); }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(44,24,16,0.08); }
        .feature-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
        .feature-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--dark); }
        .feature-card p { font-size: 16px; color: var(--text-light); line-height: 1.7; }

        /* ===== MENU PREVIEW ===== */
        .menu-preview { padding: 100px 0; background: linear-gradient(180deg, var(--cream-lighter), var(--bg)); }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .menu-card { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(44,24,16,0.04); transition: all 0.3s; border: 1px solid rgba(44,24,16,0.04); }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(44,24,16,0.08); }
        .menu-card-img { height: 180px; background: linear-gradient(135deg, var(--cream-light), var(--cream)); display: flex; align-items: center; justify-content: center; font-size: 64px; }
        .menu-card-body { padding: 20px; }
        .menu-card-cat { display: inline-block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: var(--white); background: var(--coffee); padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .menu-card-body h3 { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 10px; line-height: 1.3; }
        .menu-card-price { font-size: 20px; font-weight: 800; color: var(--coffee); letter-spacing: -0.5px; }

        /* ===== CTA ===== */
        .cta { padding: 100px 0; background: linear-gradient(135deg, var(--dark), var(--coffee)); text-align: center; position: relative; overflow: hidden; }
        .cta::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, rgba(212,165,116,0.08), transparent 70%); }
        .cta .container { position: relative; z-index: 2; }
        .cta h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3.5vw, 42px); color: white; font-weight: 800; margin-bottom: 16px; }
        .cta p { color: rgba(255,255,255,0.8); font-size: 17px; line-height: 1.6; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto; }

        /* ===== FOOTER ===== */
        .footer { background: var(--dark); padding: 40px 0 24px; text-align: center; }
        .footer-brand { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 800; color: var(--cream); margin-bottom: 8px; }
        .footer p { color: rgba(255,255,255,0.55); font-size: 13px; }
        .footer-links { display: flex; justify-content: center; gap: 24px; margin: 16px 0; }
        .footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 13px; transition: color 0.2s; }
        .footer-links a:hover { color: var(--cream); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero .container { grid-template-columns: 1fr; text-align: center; gap: 40px; }
            .hero p { margin-left: auto; margin-right: auto; }
            .hero-buttons { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-visual { order: -1; }
            .hero-cup { width: 200px; height: 200px; font-size: 90px; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="nav" id="nav">
        <div class="container">
            <a href="/" class="nav-brand">
                @if(!empty(Setting::get('site_logo')))
                    <img src="{{ asset('storage/' . Setting::get('site_logo')) }}" alt="Logo" style="height: 32px; border-radius: 6px;">
                @else
                    <span class="emoji">&#9749;</span>
                @endif
                <span class="text">{{ Setting::get('site_name', 'SiBoja') }}</span>
            </a>
            <div class="nav-links">
                <a href="#menu">Menu</a>
                <a href="#tentang">Tentang</a>
                <a href="#fitur">Fitur</a>
                <a href="#kontak">Kontak</a>
                <a href="/login" class="nav-cta">Masuk Admin</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    {{ Setting::get('hero_badge', 'Coffee Shop Terbaik di Kota') }}
                </div>
                <h1>{!! Setting::get('hero_title', 'Nikmati Setiap Tegukan <em>Kopi Terbaik</em>') !!}</h1>
                <p>{{ Setting::get('hero_subtitle', 'SiBoja Coffee menyajikan kopi premium dengan biji pilihan. Pesan langsung dari meja Anda, scan QR, pilih menu, dan bayar dengan mudah.') }}</p>
                <div class="hero-buttons">
                    <a href="#menu" class="btn-primary">
                        <i class="fas fa-coffee"></i> Lihat Menu
                    </a>
                    <a href="#tentang" class="btn-outline">
                        <i class="fas fa-play-circle"></i> Selengkapnya
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <h3>50+</h3>
                        <p>Menu Favorit</p>
                    </div>
                    <div class="hero-stat">
                        <h3>10K+</h3>
                        <p>Pelanggan Puas</p>
                    </div>
                    <div class="hero-stat">
                        <h3>4.9</h3>
                        <p>Rating</p>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-cup">&#9749;</div>
            </div>
        </div>
    </section>

    <!-- Tentang -->
    <section class="features" id="tentang">
        <div class="section-header">
            <div class="label"><i class="fas fa-info-circle"></i> Tentang Kami</div>
            <h2>{{ Setting::get('about_title', 'Kopi yang Dibuat dengan Cinta') }}</h2>
            <p>{{ Setting::get('about_description', 'Setiap cangkir yang kami sajikan adalah hasil dari dedikasi dan passion terhadap kopi berkualitas.') }}</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(212,165,116,0.1); color: var(--coffee);">&#127793;</div>
                <h3>Biji Pilihan</h3>
                <p>Kami menggunakan biji kopi pilihan dari petani terbaik Indonesia dan negara-negara penghasil kopi ternama.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(139,115,85,0.1); color: var(--coffee-light);">&#129370;</div>
                <h3>Barista Berpengalaman</h3>
                <p>Tim barista kami telah tersertasi dan berpengalaman mengolah kopi dengan teknik terbaik.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(34,197,94,0.1); color: #16a34a;">&#128187;</div>
                <h3>Pesan dari Meja</h3>
                <p>Scan QR code di meja Anda, pilih menu favorit, dan bayar secara cashless. Praktis dan cepat!</p>
            </div>
        </div>
    </section>

    <!-- Menu Preview -->
    <section class="menu-preview" id="menu">
        <div class="section-header">
            <div class="label"><i class="fas fa-mug-hot"></i> Menu Kami</div>
            <h2>Pilihan Menu Favorit</h2>
            <p>Temukan berbagai minuman dan makanan favorit yang kami sajikan dengan bahan pilihan</p>
        </div>
        <div class="menu-grid" id="menuGrid"></div>
        <div style="text-align:center; margin-top:40px;">
            <a href="/order/{{ \App\Models\Table::first()->code ?? 'MJA001' }}" class="btn-primary" style="font-size:16px; padding:16px 40px;">
                <i class="fas fa-qrcode"></i> Scan QR & Pesan Sekarang
            </a>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta" id="kontak">
        <div class="container">
            <h2>{{ Setting::get('cta_title', 'Siap Menikmati Kopi Terbaik?') }}</h2>
            <p>{{ Setting::get('cta_description', 'Temukan SiBoja Coffee terdekat atau pesan langsung dari meja Anda melalui scan QR code.') }}</p>
            <a href="#menu" class="btn-primary" style="font-size:16px; padding:16px 40px;">
                <i class="fas fa-coffee"></i> Mulai Pesan
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-brand">&#9749; {{ Setting::get('site_name', 'SiBoja Coffee') }}</div>
        <div class="footer-links">
            @if(Setting::get('site_instagram'))
                <a href="https://instagram.com/{{ ltrim(Setting::get('site_instagram'), '@') }}" target="_blank">Instagram</a>
            @endif
            @if(Setting::get('site_whatsapp'))
                <a href="https://wa.me/{{ Setting::get('site_whatsapp') }}" target="_blank">WhatsApp</a>
            @endif
            @if(Setting::get('site_tiktok'))
                <a href="https://tiktok.com/{{ ltrim(Setting::get('site_tiktok'), '@') }}" target="_blank">TikTok</a>
            @endif
            @if(Setting::get('site_facebook'))
                <a href="https://facebook.com/{{ Setting::get('site_facebook') }}" target="_blank">Facebook</a>
            @endif
        </div>
        <p>&copy; {{ date('Y') }} {{ Setting::get('site_name', 'SiBoja Coffee') }}. {{ Setting::get('footer_text', 'All rights reserved.') }}</p>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 50);
        });

        // Load menu from API
        fetch('/api/menu')
        .then(r => r.json())
        .then(function(products) {
            var grid = document.getElementById('menuGrid');
            var emojis = ['&#9749;', '&#127861;', '&#129380;', '&#127856;', '&#129361;', '&#127854;'];
            products.slice(0, 6).forEach(function(p, i) {
                var card = document.createElement('div');
                card.className = 'menu-card';
                var stockBadge = p.stock > 0
                    ? '<span style="display:inline-block; font-size:11px; font-weight:600; color:#16a34a; margin-top:8px;"><i class="fas fa-check-circle"></i> Tersedia</span>'
                    : '<span style="display:inline-block; font-size:11px; font-weight:600; color:#dc2626; margin-top:8px;"><i class="fas fa-times-circle"></i> Habis</span>';
                card.innerHTML =
                    '<div class="menu-card-img">' + emojis[i % emojis.length] + '</div>' +
                    '<div class="menu-card-body">' +
                        '<div class="menu-card-cat">' + (p.category ? p.category.name : '') + '</div>' +
                        '<h3>' + p.name + '</h3>' +
                        '<div class="menu-card-price">Rp' + new Intl.NumberFormat('id-ID').format(p.price) + '</div>' +
                        stockBadge +
                    '</div>';
                grid.appendChild(card);
            });
        });
    </script>
</body>
</html>
